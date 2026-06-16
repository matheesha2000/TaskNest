@extends('layouts.app')
@section('title', 'My Tasks')

@section('header-actions')
    <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Task
    </a>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Pro: Search & Filters --}}
    @if(auth()->user()->isPro())
    <form method="GET" action="{{ route('tasks.index') }}" class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <div class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search tasks…"
                   class="flex-1 min-w-48 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">

            <select name="status" class="rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <option value="">All statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>

            <select name="priority" class="rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <option value="">All priorities</option>
                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
            </select>

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 transition-colors">Filter</button>
            <a href="{{ route('tasks.index') }}" class="px-4 py-2 rounded-lg text-sm text-gray-500 hover:bg-gray-100 border border-gray-200 transition-colors">Clear</a>
        </div>
    </form>
    @endif

    {{-- Stats row --}}
    <div class="grid grid-cols-4 gap-3">
        @foreach(['Total' => $stats['total'], 'Pending' => $stats['pending'], 'In Progress' => $stats['in_progress'], 'Completed' => $stats['completed']] as $label => $count)
        <div class="bg-white rounded-lg border border-gray-100 px-4 py-3 text-center shadow-sm">
            <p class="text-2xl font-bold text-gray-800">{{ $count }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $label }}</p>
        </div>
        @endforeach
    </div>

    {{-- Task table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        @if($tasks->isEmpty())
        <div class="py-16 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-gray-400 text-sm">No tasks found.</p>
            <a href="{{ route('tasks.create') }}" class="mt-3 inline-block text-sm text-indigo-600 hover:underline">Create your first task →</a>
        </div>
        @else
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Task</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Status</th>
                    @if(auth()->user()->isPro())
                    <th class="text-left px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Priority</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Category</th>
                    @endif
                    <th class="text-left px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Due Date</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($tasks as $task)
                <tr class="hover:bg-gray-50 transition-colors {{ $task->isOverdue() ? 'bg-red-50/30' : '' }}">
                    <td class="px-6 py-4">
                        <a href="{{ route('tasks.show', $task) }}" class="font-medium text-gray-800 hover:text-indigo-600 {{ $task->status === 'completed' ? 'line-through text-gray-400' : '' }}">
                            {{ $task->title }}
                        </a>
                        @if($task->description)
                        <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $task->description }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->statusBadgeClass() }}">
                            {{ str_replace('_', ' ', ucfirst($task->status)) }}
                        </span>
                    </td>
                    @if(auth()->user()->isPro())
                    <td class="px-4 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->priorityBadgeClass() }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-gray-500 text-xs">{{ $task->category ?? '—' }}</td>
                    @endif
                    <td class="px-4 py-4">
                        @if($task->due_date)
                            <span class="text-xs {{ $task->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                {{ $task->due_date->format('M d, Y') }}
                                @if($task->isOverdue()) <span class="text-red-500">overdue</span> @endif
                            </span>
                        @else
                            <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-2 justify-end">
                            <a href="{{ route('tasks.edit', $task) }}" class="text-gray-400 hover:text-indigo-600" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($tasks->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $tasks->withQueryString()->links() }}
        </div>
        @endif
        @endif
    </div>

    {{-- Free plan limit indicator --}}
    @if(!auth()->user()->isPro())
    <div class="text-xs text-gray-400 text-center">
        Using {{ $stats['total'] }} / 10 free tasks.
        @if($stats['total'] >= 8)
            <a href="{{ route('subscription.index') }}" class="text-indigo-500 hover:underline ml-1">Upgrade to Pro for unlimited tasks →</a>
        @endif
    </div>
    @endif
</div>
@endsection