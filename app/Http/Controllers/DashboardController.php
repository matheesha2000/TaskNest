<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total'       => $user->tasks()->count(),
            'pending'     => $user->tasks()->pending()->count(),
            'in_progress' => $user->tasks()->inProgress()->count(),
            'completed'   => $user->tasks()->completed()->count(),
        ];

        $recentTasks = $user->tasks()
            ->latest()
            ->take(5)
            ->get();

        $overdueTasks = $user->isPro()
            ? $user->tasks()->overdue()->get()
            : collect();

        return view('dashboard', compact('stats', 'recentTasks', 'overdueTasks'));
    }
}