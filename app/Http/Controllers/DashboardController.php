<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total'       => $user->tasks()->count(),
            'pending'     => $user->tasks()->pending()->count(),
            'in_progress' => $user->tasks()->inProgress()->count(),
            'completed'   => $user->tasks()->completed()->count(),
        ];

        $recentTasks = $user->tasks()->latest()->take(5)->get();

        $overdueTasks = $user->isPro()
            ? $user->tasks()->overdue()->get()
            : collect();

        return view('dashboard', compact('stats', 'recentTasks', 'overdueTasks'));
    }
}