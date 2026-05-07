<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{

    /**
     * Show Payment QR Code for Sepay method
     * Author: Minh Hieu
     * @param mixed $payment_id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($payment_id)
    {
        $payment = Payment::findOrFail($payment_id);
        $order = $payment->order;
        $type = $order->details->first()->product_type;
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($order->user_id !== $user->id) {
            return redirect()->back()->with('error', 'You do not have permission access.');
        }
        $order_id = $order->id;
        $orderDetails = $order->details()->first();
        $product_id = $orderDetails->product_id;

        $code = $order->code;
        $code = $code . '_' . $payment_id;
        $total_amount = $order->total_amount;
        $total_to_vnd = $total_amount * config('settings.currency.usd_to_vnd');
        return view('home.qr-payment', compact('code', 'total_to_vnd', 'order_id', 'product_id', 'payment', 'payment_id', 'type'));
    }

    public function checkStatus(Request $request, $payment_id)
    {
        $payment = Payment::findOrFail($payment_id);
        return response()->json([
            'payment_status' => $payment->status
        ]);
    }

    /**
     * Format code to UUID
     * Author: Minh Hieu
     * @param string $raw
     * @return string|null
     */
    public function formatToUuid(string $raw): ?string
    {
        if (!preg_match('/^[0-9a-fA-F]{32}$/', $raw)) {
            return null;
        }

        return vsprintf('%s-%s-%s-%s-%s', [
            substr($raw, 0, 8),
            substr($raw, 8, 4),
            substr($raw, 12, 4),
            substr($raw, 16, 4),
            substr($raw, 20),
        ]);
    }

    /**
     * Handle Sepay webhook
     * Author: Minh Hieu
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function webhook(Request $request)
    {
        try {
            Log::info('Sepay Webhook received:', $request->all());

            $data = $request->all();
            $transactionId   = $data['id'] ?? null;
            $transferAmount  = $data['transferAmount'] ?? null;
            $content         = $data['content'] ?? null;
            $transferType    = $data['transferType'] ?? null;
            $order = null;

            if (!$transactionId || !$transferAmount || !$transferType) {
                Log::warning('Missing required fields');
                return response()->json([
                    'success' => false,
                    'message' => 'Missing required fields'
                ], 400);
            }

            $client = new \GuzzleHttp\Client();
            $response = $client->get(config('settings.sepay.url_base') . '/transactions/details/' . $transactionId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('settings.sepay.api_key'),
                    'Accept'        => 'application/json',
                ]
            ]);

            $apiData = json_decode($response->getBody(), true);
            $transaction = $apiData['transaction'] ?? null;

            if (!$transaction || $transaction['id'] != $transactionId) {
                Log::warning('Transaction not found or ID mismatch');
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found or ID mismatch'
                ], 200);
            }

            $content = $transaction['transaction_content'] ?? '';
            if (preg_match('/([a-f0-9]{32})(\d+)/', $content, $matches)) {
                $rawTransactionCode = $matches[1];
                $paymentId = (int) $matches[2];

                $transactionCode = $this->formatToUuid($rawTransactionCode);

                Log::info('Parsed transaction content', [
                    'transactionCode' => $transactionCode,
                    'paymentId' => $paymentId
                ]);
            } else {
                $transactionCode = null;
                $paymentId = null;
                Log::warning('Cannot parse transaction content', ['content' => $content]);
            }

            if (!$transactionCode) {
                Log::warning('Invalid transaction code format');
                return response()->json([
                    'success' => true
                ]);
            }

            $order = Order::where('code', '=', $transactionCode)
                ->where('status', '=', 'pending')
                ->first();

            if (!$order) {
                Log::warning('Not Found Order or Order status is not pending', ['code' => $transactionCode]);
                return response()->json([
                    'success' => true
                ]);
            }

            $amountIn = (float) $transaction['amount_in'];
            if (bccomp((string)$amountIn, (string)($order->total_amount * config('settings.currency.usd_to_vnd')), 2) === 0) {

                $payment = $order->payments()
                    ->where('id', $paymentId)
                    ->where('status', 'pending')
                    ->first();

                if ($payment) {
                    $payment->update([
                        'status' => 'success',
                        'paid_at' => now(),
                        'transaction_code' => $transactionId,
                        'response_data' => json_encode($data),
                    ]);

                    Log::info('Payment updated to success', ['payment_id' => $payment->id]);

                    $order->payments()
                        ->where('id', '!=', $payment->id)
                        ->where('status', 'pending')
                        ->update([
                            'status' => 'expired',
                        ]);

                    Log::info('Other pending payments set to expired', ['order_id' => $order->id]);

                    $order = $payment->order;
                    $user = $order->user;
                    $orderDetail = $order->details()->first();
                    Log::info('Order detail fetched', ['order_detail_id' => $orderDetail->id]);
                    $productId = $orderDetail->product_id;
                    $productType = $orderDetail->product_type;
                    Log::info('Product type and ID', ['product_type' => $productType, 'product_id' => $productId]);
                    // Handle different product types
                    if ($productType === 'courses') {
                        // Enroll user to course
                        if (!$user->enrolledCourses()->where('course_id', $productId)->exists()) {
                            $user->enrolledCourses()->attach($productId, ['enrolled_at' => now()]);
                            Log::info("User enrolled to course", ['user_id' => $user->id, 'course_id' => $productId]);
                        }
                    } elseif ($productType === 'events') {
                        // Create event ticket for user
                        if (!$user->boughtTickets()->where('event_id', $productId)->exists()) {
                            $user->boughtTickets()->attach($productId, [
                                'cost' => $orderDetail->price,
                                'status' => 'paid',
                                // 'purchased_at' => now()
                            ]);
                            Log::info("User purchased event ticket", ['user_id' => $user->id, 'event_id' => $productId]);
                        }
                    } else {
                        Log::warning("Unknown product type", ['product_type' => $productType, 'product_id' => $productId]);
                    }
                }

                $order->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
                Log::info('Order updated to completed', ['order_id' => $order->id]);
            } else {
                Log::warning('Amount mismatch');
            }

            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Sepay Webhook Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Webhook processing failed'
            ], 500);
        }
    }

    /**
     * Update payment status to expired
     * Author: Minh Hieu
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $status = $request->status;

        if ($status === 'expired') {
            $payment->update([
                'status' => 'expired',
                'expires_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid status'
        ], 400);
    }
}
