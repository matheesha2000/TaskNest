<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List tasks — with optional search/filter (Pro users only)
     */
    public function index(Request $request)
    {
        $user  = auth()->user();
        $query = $user->tasks()->latest();

        // Pro-only: search & filters
        if ($user->isPro()) {
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }
        }

        $tasks = $query->paginate(10);

        // Stats for dashboard widget
        $stats = [
            'total'       => $user->tasks()->count(),
            'pending'     => $user->tasks()->pending()->count(),
            'in_progress' => $user->tasks()->inProgress()->count(),
            'completed'   => $user->tasks()->completed()->count(),
            'overdue'     => $user->isPro() ? $user->tasks()->overdue()->count() : 0,
        ];

        return view('tasks.index', compact('tasks', 'stats'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $user = auth()->user();

        if ($user->hasReachedTaskLimit()) {
            return redirect()->route('tasks.index')
                ->with('warning', 'Free plan limit reached (10 tasks). Upgrade to Pro for unlimited tasks.');
        }

        return view('tasks.create');
    }

    /**
     * Store a new task
     */
    public function store(StoreTaskRequest $request)
    {
        $user = auth()->user();

        if ($user->hasReachedTaskLimit()) {
            return redirect()->route('subscription.index')
                ->with('warning', 'Task limit reached. Upgrade to Pro for unlimited tasks.');
        }

        $data = $request->validated();

        // Free users: strip pro-only fields
        if (!$user->isPro()) {
            $data['priority'] = 'medium';
            $data['category'] = null;
        }

        $user->tasks()->create($data);

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Show a single task
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return view('tasks.show', compact('task'));
    }

    /**
     * Show edit form
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update task
     */
    public function update(StoreTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $data = $request->validated();

        if (!auth()->user()->isPro()) {
            $data['priority'] = 'medium';
            $data['category'] = null;
        }

        $task->update($data);

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Delete task
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted.');
    }

    /**
     * Quick status update via AJAX or form post
     */
    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update(['status' => $request->status]);

        return back()->with('success', 'Status updated.');
    }
}