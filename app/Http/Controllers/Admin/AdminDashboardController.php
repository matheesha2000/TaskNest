<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Task;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'      => User::count(),
            'new_users_week'   => User::where('created_at', '>=', now()->subDays(7))->count(),
            'total_tasks'      => Task::count(),
            'completed_tasks'  => Task::where('status', 'completed')->count(),
            'pending_tasks'    => Task::where('status', 'pending')->count(),
            'inprogress_tasks' => Task::where('status', 'in_progress')->count(),
            'overdue_tasks'    => Task::where('status', '!=', 'completed')
                                      ->whereNotNull('due_date')
                                      ->where('due_date', '<', now())
                                      ->count(),
            'pro_users'        => User::whereHas('subscription', fn ($q) => $q->where('price', '>', 0))->count(),
            'free_users'       => User::whereHas('subscription', fn ($q) => $q->where('price', 0))
                                      ->orWhereNull('subscription_id')->count(),
            'total_revenue'    => Payment::where('payment_status', 'completed')->sum('amount'),
            'revenue_week'     => Payment::where('payment_status', 'completed')
                                         ->where('paid_at', '>=', now()->subDays(7))->sum('amount'),
            'total_payments'   => Payment::where('payment_status', 'completed')->count(),
            'avg_rating'       => round(Review::avg('rating') ?? 0, 1),
            'total_reviews'    => Review::count(),
        ];

        // Revenue by month (last 6 months) for mini chart data
        $revenueChart = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            return [
                'month'   => $date->format('M'),
                'revenue' => Payment::where('payment_status', 'completed')
                    ->whereYear('paid_at', $date->year)
                    ->whereMonth('paid_at', $date->month)
                    ->sum('amount'),
            ];
        });

        $recentPayments = Payment::with('user', 'subscription')->latest('paid_at')->take(6)->get();
        $recentUsers    = User::with('subscription')->latest()->take(6)->get();

        return view('admin.dashboard', compact('stats', 'recentPayments', 'recentUsers', 'revenueChart'));
    }
}