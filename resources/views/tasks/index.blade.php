@extends('layouts.app')
@section('title', 'My Tasks')

@section('header-actions')
    <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Task
    </a>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Pro: Search & Filters --}}
    @if(auth()->user()->isPro())
    <form method="GET" action="{{ route('tasks.index') }}" class="glass-card rounded-2xl p-5 shadow-sm border border-slate-200/60">
        <div class="flex flex-wrap gap-4 items-center">
            <div class="flex-1 min-w-[240px] relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search tasks by title..."
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 transition-all">
            </div>

            <div class="min-w-[160px]">
                <select name="status" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 transition-all">
                    <option value="">All statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="min-w-[160px]">
                <select name="priority" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 transition-all">
                    <option value="">All priorities</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="bg-indigo-600 text-white font-semibold px-5 py-2.5 rounded-xl text-sm hover:bg-indigo-700 hover:shadow-md transition-all">
                    Filter
                </button>
                <a href="{{ route('tasks.index') }}" class="px-5 py-2.5 rounded-xl text-sm text-slate-600 hover:bg-slate-50 border border-slate-200 hover:text-slate-800 font-semibold transition-all">
                    Clear
                </a>
            </div>
        </div>
    </form>
    @endif

    {{-- Stats row --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            'Total' => ['count' => $stats['total'], 'color' => 'text-slate-600 bg-slate-100 border-slate-200'],
            'Pending' => ['count' => $stats['pending'], 'color' => 'text-amber-600 bg-amber-55 border-amber-200/50'],
            'In Progress' => ['count' => $stats['in_progress'], 'color' => 'text-blue-600 bg-blue-55 border-blue-200/50'],
            'Completed' => ['count' => $stats['completed'], 'color' => 'text-emerald-600 bg-emerald-55 border-emerald-200/50']
        ] as $label => $info)
        <div class="glass-card rounded-xl px-5 py-3.5 flex items-center justify-between border border-slate-200/60 shadow-sm">
            <div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">{{ $label }}</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-1 tracking-tight">{{ $info['count'] }}</p>
            </div>
            <div class="w-3 h-3 rounded-full {{ explode(' ', $info['color'])[1] }} border {{ explode(' ', $info['color'])[2] }} animate-pulse"></div>
        </div>
        @endforeach
    </div>

    {{-- Task table --}}
    <div class="glass-card rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
        @if($tasks->isEmpty())
        <div class="py-20 text-center">
            <svg class="w-12 h-12 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-slate-500 text-sm font-medium">No tasks found in your Nest.</p>
            <a href="{{ route('tasks.create') }}" class="mt-4 inline-flex items-center gap-2 bg-indigo-600 text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-indigo-700 hover:shadow-md transition-all">
                Create your first task →
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left glass-table">
                <thead>
                    <tr class="border-b border-slate-200/60 bg-slate-50">
                        <th class="px-6 py-4 font-bold text-slate-500 text-xs uppercase tracking-wider">Task</th>
                        <th class="px-4 py-4 font-bold text-slate-500 text-xs uppercase tracking-wider">Status</th>
                        @if(auth()->user()->isPro())
                        <th class="px-4 py-4 font-bold text-slate-500 text-xs uppercase tracking-wider">Priority</th>
                        <th class="px-4 py-4 font-bold text-slate-500 text-xs uppercase tracking-wider">Category</th>
                        @endif
                        <th class="px-4 py-4 font-bold text-slate-500 text-xs uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($tasks as $task)
                    <tr class="hover:bg-slate-50/70 transition-all duration-150 {{ $task->isOverdue() ? 'bg-rose-50/40' : '' }}">
                        <td class="px-6 py-4.5">
                            <a href="{{ route('tasks.show', $task) }}" class="font-bold text-slate-800 hover:text-indigo-600 transition-colors {{ $task->status === 'completed' ? 'line-through text-slate-400 hover:text-slate-500' : '' }}">
                                {{ $task->title }}
                            </a>
                            @if($task->description)
                            <p class="text-xs text-slate-500 mt-1 truncate max-w-sm font-normal">{{ $task->description }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-4.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide border {{ $task->statusBadgeClass() }}">
                                {{ str_replace('_', ' ', strtoupper($task->status)) }}
                            </span>
                        </td>
                        @if(auth()->user()->isPro())
                        <td class="px-4 py-4.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide border {{ $task->priorityBadgeClass() }}">
                                {{ strtoupper($task->priority) }}
                            </span>
                        </td>
                        <td class="px-4 py-4.5">
                            @if($task->category)
                                <span class="bg-indigo-50 text-indigo-700 border border-indigo-100 px-2.5 py-0.5 rounded-lg text-[11px] font-semibold">
                                    {{ $task->category }}
                                </span>
                            @else
                                <span class="text-slate-450 font-mono">—</span>
                            @endif
                        </td>
                        @endif
                        <td class="px-4 py-4.5">
                            @if($task->due_date)
                                <span class="text-xs font-semibold {{ $task->isOverdue() ? 'text-red-600 font-semibold' : 'text-slate-500' }}">
                                    {{ $task->due_date->format('M d, Y') }}
                                    @if($task->isOverdue()) <span class="ml-1 text-[10px] uppercase font-extrabold tracking-wide px-1.5 py-0.2 bg-red-100 border border-red-200 text-red-700">overdue</span> @endif
                                </span>
                            @else
                                <span class="text-xs text-slate-400 font-mono">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4.5 text-right">
                            <div class="flex items-center gap-3 justify-end">
                                <a href="{{ route('tasks.edit', $task) }}" class="text-slate-400 hover:text-indigo-600 p-1.5 rounded-lg hover:bg-slate-100 transition-all" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task from your Nest?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-600 p-1.5 rounded-lg hover:bg-slate-100 transition-all" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($tasks->hasPages())
        <div class="px-6 py-4 border-t border-slate-200/60 bg-slate-50">
            {{ $tasks->withQueryString()->links() }}
        </div>
        @endif
    </div>

    {{-- Free plan limit indicator --}}
    @if(!auth()->user()->isPro())
    <div class="text-xs text-slate-500 text-center flex items-center justify-center gap-2">
        <span>Nest Limit:</span>
        <div class="w-32 bg-slate-200 rounded-full h-1.5 overflow-hidden">
            <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ min(100, ($stats['total'] / 10) * 100) }}%"></div>
        </div>
        <span class="font-bold text-slate-600">{{ $stats['total'] }} / 10 tasks</span>
        @if($stats['total'] >= 8)
            <a href="{{ route('subscription.index') }}" class="text-indigo-600 hover:underline ml-1 font-semibold">Upgrade to Pro for unlimited space →</a>
        @endif
    </div>
    @endif
</div>
@endsection