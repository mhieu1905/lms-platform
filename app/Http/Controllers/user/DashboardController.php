<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Event;
use App\Models\News;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        // check role
        $isAdmin = $user->hasRole('admin');
        $isTeacher = $user->hasRole('teacher');

        if ($isAdmin) {
            $totalAmount = Order::where('status', 'paid')->sum('total_amount');

            $totalRevenueCourses = OrderDetail::whereHas('order', function ($q) {
                $q->where('status', 'paid');
            })
                ->where('product_type', 'courses')
                ->sum(DB::raw('quantity * price'));
            Log::info('Total Revenue Courses: ' . $totalRevenueCourses);

            $totalRevenueEvents = OrderDetail::whereHas('order', function ($q) {
                $q->where('status', 'paid');
            })
                ->where('product_type', 'events')
                ->sum(DB::raw('quantity * price'));
            Log::info('Total Revenue Events: ' . $totalRevenueEvents);

            $courseStats = Course::selectRaw('COUNT(*) as total, SUM(status = 1) as published')->first();
            $totalCourses = $courseStats->total;
            $totalPublishedCourses = $courseStats->published;

            $totalUsers = User::count();
            $roleCounts = DB::table('role_user')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('roles.name', DB::raw('COUNT(role_user.user_id) as total'))
                ->whereIn('roles.name', ['student', 'teacher', 'admin'])
                ->groupBy('roles.name')
                ->pluck('total', 'roles.name');
            $students = $roleCounts['student'] ?? 0;
            $teachers = $roleCounts['teacher'] ?? 0;
            $admins   = $roleCounts['admin'] ?? 0;

            // Top courses have hightest revenue
            $topCourses = OrderDetail::selectRaw('
                product_id, 
                product_type,
                SUM(quantity) as total_quantity, 
                SUM(subtotal) as total_revenue')
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->where('orders.status', 'paid')
                ->where('product_type', 'courses')
                ->groupBy('product_id', 'product_type')
                ->orderBy('total_revenue', 'desc')
                ->limit(10)
                ->get();
            $topCourses->load('product.user');

            // Revenue chart
            $monthlyRevenue = DB::table('order_details')
                ->join('orders', 'orders.id', '=', 'order_details.order_id')
                ->where('orders.status', 'paid')
                ->whereYear('orders.created_at', date('Y'))
                ->select(
                    DB::raw('MONTH(orders.created_at) as month'),
                    DB::raw('SUM(order_details.quantity * order_details.price) as revenue')
                )
                ->groupBy(DB::raw('MONTH(orders.created_at)'))
                ->orderBy('month')
                ->pluck('revenue', 'month');


        } elseif ($isTeacher) {
            $totalAmount = Order::where('status', 'paid')
                ->whereHas('details', function ($q) use ($user) {
                    $q->whereIn('product_id', function ($q2) use ($user) {
                        $q2->select('id')
                            ->from('courses')
                            ->where('user_id', $user->id);
                    });
                })
                ->sum('total_amount');

            $totalCourses = Course::where('user_id', $user->id)->count();
            $totalPublishedCourses = Course::where('user_id', $user->id)->where('status', 1)->count();
            $totalUsers = $students = $teachers = $admins = null; // dont show total user for teacher

            // top 5 hightest coures revenue
            $topCourses = OrderDetail::selectRaw('
                product_id, 
                product_type,
                SUM(quantity) as total_quantity, 
                SUM(subtotal) as total_revenue')
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->join('courses', 'order_details.product_id', '=', 'courses.id') 
                ->where('orders.status', 'paid')
                ->where('product_type', 'courses')
                ->where('courses.user_id', $user->id)
                ->groupBy('product_id', 'product_type')
                ->orderBy('total_revenue', 'desc')
                ->limit(10)
                ->get();
            $topCourses->load('product.user');

            // Chart
            $monthlyRevenue = DB::table('orders')
                ->select(
                    DB::raw('MONTH(orders.created_at) as month'),
                    DB::raw('SUM(order_details.quantity * order_details.price) as revenue')
                )
                ->join('order_details', 'order_details.order_id', '=', 'orders.id')
                ->join('courses', 'courses.id', '=', 'order_details.product_id')
                ->where('orders.status', 'paid')
                ->where('courses.user_id', $user->id)
                ->whereYear('orders.created_at', date('Y'))
                ->groupBy(DB::raw('MONTH(orders.created_at)'))
                ->orderBy('month')
                ->pluck('revenue', 'month');
        } else {
            abort(403, 'Unauthorized');
        }

        return view('admin.dashboard.index', compact(
            'totalAmount',
            'totalUsers',
            'totalCourses',
            'totalPublishedCourses',
            'students',
            'teachers',
            'admins',
            'topCourses',
            'monthlyRevenue',
            'isAdmin',
            'isTeacher',
        ));
    }
}
