@extends('layouts.admin')
@section('title', 'Task Monitor')
@section('subtitle', 'View all tasks across all users')

@section('content')
<div class="space-y-5">

    {{-- ── Filter Bar ───────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4">
        <form method="GET" action="{{ route('admin.tasks') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-48">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by task title or user name…"
                       class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <select name="status" class="text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500">
                <option value="">All Statuses</option>
                <option value="pending"     {{ request('status') === 'pending'     ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed"   {{ request('status') === 'completed'   ? 'selected' : '' }}>Completed</option>
            </select>

            <select name="priority" class="text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500">
                <option value="">All Priorities</option>
                <option value="high"   {{ request('priority') === 'high'   ? 'selected' : '' }}>High</option>
                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="low"    {{ request('priority') === 'low'    ? 'selected' : '' }}>Low</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-brand-600 text-white rounded-xl text-sm font-medium hover:bg-brand-700 transition-colors">
                Filter
            </button>

            @if(request()->hasAny(['search','status','priority']))
                <a href="{{ route('admin.tasks') }}" class="text-sm text-slate-400 hover:text-slate-600">Clear</a>
            @endif

            <span class="ml-auto text-xs text-slate-400">{{ $tasks->total() }} tasks</span>
        </form>
    </div>

    {{-- ── Tasks Table ──────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Task</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Owner</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Priority</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Due Date</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Created</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($tasks as $task)
                    @php
                        $isOverdue = $task->due_date
                            && \Carbon\Carbon::parse($task->due_date)->isPast()
                            && $task->status !== 'completed';
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors {{ $isOverdue ? 'bg-red-50/30' : '' }}">

                        {{-- Task title --}}
                        <td class="px-5 py-4 max-w-xs">
                            <p class="font-medium text-slate-800 truncate">{{ $task->title }}</p>
                            @if($task->category)
                                <span class="text-xs text-slate-400">{{ $task->category }}</span>
                            @endif
                        </td>

                        {{-- Owner --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($task->user->name ?? '?', 0, 1)) }}
                                </div>
                                <span class="text-slate-600 text-xs">{{ $task->user->name ?? '—' }}</span>
                            </div>
                        </td>

                        {{-- Status --}}
                        <td class="px-5 py-4">
                            @php
                                $statusBadge = match($task->status) {
                                    'completed'   => 'bg-green-100 text-green-700',
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    default       => 'bg-slate-100 text-slate-600',
                                };
                                $statusLabel = match($task->status) {
                                    'completed'   => '✓ Completed',
                                    'in_progress' => '● In Progress',
                                    default       => '○ Pending',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadge }}">
                                {{ $statusLabel }}
                            </span>
                        </td>

                        {{-- Priority --}}
                        <td class="px-5 py-4">
                            @if($task->priority)
                                @php
                                    $prioBadge = match($task->priority) {
                                        'high'   => 'bg-red-100 text-red-700',
                                        'medium' => 'bg-amber-100 text-amber-700',
                                        'low'    => 'bg-green-100 text-green-700',
                                        default  => 'bg-slate-100 text-slate-500',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prioBadge }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            @else
                                <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>

                        {{-- Due date --}}
                        <td class="px-5 py-4 text-xs {{ $isOverdue ? 'text-red-600 font-medium' : 'text-slate-400' }}">
                            @if($task->due_date)
                                {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                @if($isOverdue)
                                    <span class="block text-red-400">(overdue)</span>
                                @endif
                            @else
                                <span class="text-slate-300">—</span>
                            @endif
                        </td>

                        {{-- Created --}}
                        <td class="px-5 py-4 text-xs text-slate-400">
                            {{ $task->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                            No tasks found matching your filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($tasks->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>

</div>
@endsection