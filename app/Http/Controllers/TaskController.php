<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * List tasks — with optional search/filter (Pro users only)
     */
    public function index(Request $request)
    {
        $user  = Auth::user();
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
        $user = Auth::user();

        if ($user->hasReachedTaskLimit()) {
            return redirect()->route('tasks.index')
                ->with('warning', 'Free plan limit reached. Upgrade to Pro for more tasks.');
        }

        return view('tasks.create');
    }

    /**
     * Store task
     */
    public function store(StoreTaskRequest $request)
    {
        $user = Auth::user();

        if ($user->hasReachedTaskLimit()) {
            return redirect()->route('subscription.index')
                ->with('warning', 'Upgrade to Pro for unlimited tasks.');
        }

        $data = $request->validated();

        if (!$user->isPro()) {
            $data['priority'] = 'medium';
            $data['category'] = null;
        }

        $user->tasks()->create($data);

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Show task
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return view('tasks.show', compact('task'));
    }

    /**
     * Edit task
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

        if (!Auth::user()->isPro()) {
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
     * Update status
     */
    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status updated.');
    }
}