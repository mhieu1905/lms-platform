<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Event;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Return order view
     * Author: Minh Hieu
     * @param mixed $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create($type, $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($type === 'events') {
            $product = Event::findOrFail($id);
            $price = $product->cost;
        } elseif ($type === 'courses') {
            $product = Course::withCount('lessons')->findOrFail($id);
            $price = $product->sale_price ?? $product->regular_price;

            if ($user->enrolledCourses()->where('course_id', $product->id)->exists()) {
                return redirect()->back()->with('error', 'You have already enrolled in this course.');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid product type.');
        }

        return view('home.orders', compact('product', 'price', 'type'));
    }

    /**
     * Store order by Sepay method
     * Author: Minh Hieu
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $productType = $request->type;
        $productId = $request->product_id;
        DB::beginTransaction();

        try {
            $order = Order::where('user_id', $user->id)
                ->where('status', '=', 'pending')
                ->whereHas('details', function ($query) use ($productId, $productType) {
                    $query->where('product_id', $productId)
                        ->where('product_type', $productType);
                })
                ->first();

            if (!$order) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'note' => $request->note ?? '',
                    'status' => 'pending',
                    'total_amount' => $request->price,
                    'code' => (string) Str::uuid(),
                ]);

                $order->details()->create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'product_type' => $productType,
                    'quantity' => $request->quantity,
                    'price' => $request->price,
                    'subtotal' => $request->price * $request->quantity,
                ]);
            }

            $payment = $order->payments()->create([
                'payment_method' => $request->payment,
                'transaction_code' => '',
                'amount' => $request->price,
                'status' => 'pending',
                'expires_at' => now()->addMinutes(config('settings.payment_qr.expires_at')),
            ]);

            DB::commit();
            return redirect()->route('payments.show', $payment->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Can not create order:' . $e->getMessage());
        }
    }

    /**
     * Store order by PayPal method
     * Author: Minh Hieu
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderPayPal(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated'], 401);
        }

        $data = $request->all();

        $clientId = config('settings.paypal.client_id');
        $secret = config('settings.paypal.client_secret');
        $accessTokenResponse = Http::withBasicAuth($clientId, $secret)
            ->asForm()
            ->post(config('settings.paypal.oauth_url'), ['grant_type' => 'client_credentials']);
        $accessToken = $accessTokenResponse->json()['access_token'];

        $orderResponse = Http::withToken($accessToken)
            ->get(config('settings.paypal.order_url') . "/{$data['orderID']}");
        $orderData = $orderResponse->json();
        Log::info('PayPal order verify', $orderData);
        if ($orderData['status'] !== 'COMPLETED') {
            return response()->json(['status' => 'error', 'message' => 'PayPal order not paid'], 400);
        }

        $amountPaid = $orderData['purchase_units'][0]['amount']['value'];
        if ($amountPaid != $data['price']) {
            return response()->json(['status' => 'error', 'message' => 'Amount mismatch'], 400);
        }

        DB::beginTransaction();
        try {
            $course = Course::findOrFail($data['course_id']);
            if ($user->enrolledCourses()->where('course_id', $course->id)->exists()) {
                return response()->json(['status' => 'error', 'message' => 'Already enrolled'], 400);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'note' => $data['note'] ?? '',
                'status' => 'paid',
                'total_amount' => $amountPaid,
                'code' => (string) Str::uuid(),
            ]);
            $order->details()->create([
                'order_id' => $order->id,
                'product_id' => $course->id,
                'product_type' => Course::class,
                'quantity' => 1,
                'price' => $amountPaid,
                'subtotal' => $amountPaid,
            ]);
            $order->payments()->create([
                'payment_method' => 'paypal',
                'transaction_code' => $data['orderID'],
                'amount' => $amountPaid,
                'status' => 'success',
                'response_data' => json_encode($orderData),
                'paid_at' => now()
            ]);

            $user->enrolledCourses()->attach($course->id);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Payment successful and course enrolled.',
                'redirect_url' => route('courses.show', $course->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PayPal order error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
