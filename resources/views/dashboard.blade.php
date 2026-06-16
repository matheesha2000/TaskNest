@extends('layouts.app')
@section('title', 'Dashboard')

@section('header-actions')
    <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Task
    </a>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Stats cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Total Tasks',   'value' => $stats['total'],       'color' => 'indigo'],
            ['label' => 'Pending',        'value' => $stats['pending'],     'color' => 'yellow'],
            ['label' => 'In Progress',    'value' => $stats['in_progress'], 'color' => 'blue'],
            ['label' => 'Completed',      'value' => $stats['completed'],   'color' => 'green'],
        ] as $card)
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $card['label'] }}</p>
            <p class="text-3xl font-bold text-{{ $card['color'] }}-600 mt-1">{{ $card['value'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Overdue alert (Pro only) --}}
    @if(auth()->user()->isPro() && $overdueTasks->count() > 0)
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <div>
            <p class="text-sm font-semibold text-red-800">{{ $overdueTasks->count() }} overdue task{{ $overdueTasks->count() > 1 ? 's' : '' }}</p>
            <p class="text-xs text-red-600 mt-0.5">{{ $overdueTasks->pluck('title')->implode(', ') }}</p>
        </div>
    </div>
    @endif

    {{-- Free plan upgrade nudge --}}
    @if(!auth()->user()->isPro())
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl p-5 text-white flex items-center justify-between">
        <div>
            <p class="font-semibold">Upgrade to Pro</p>
            <p class="text-sm text-indigo-100 mt-0.5">Unlock unlimited tasks, priorities, categories & analytics.</p>
        </div>
        <a href="{{ route('subscription.index') }}" class="bg-white text-indigo-700 text-sm font-semibold px-4 py-2 rounded-lg hover:bg-indigo-50 transition-colors flex-shrink-0 ml-4">
            Upgrade — $9.99/mo
        </a>
    </div>
    @endif

    {{-- Recent tasks --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Recent Tasks</h2>
            <a href="{{ route('tasks.index') }}" class="text-sm text-indigo-600 hover:underline">View all</a>
        </div>

        @if($recentTasks->isEmpty())
        <div class="py-12 text-center">
            <p class="text-gray-400 text-sm">No tasks yet.</p>
            <a href="{{ route('tasks.create') }}" class="mt-3 inline-block text-sm text-indigo-600 hover:underline">Create your first task →</a>
        </div>
        @else
        <ul class="divide-y divide-gray-50">
            @foreach($recentTasks as $task)
            <li class="flex items-center gap-4 px-6 py-3 hover:bg-gray-50 transition-colors">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $task->statusBadgeClass() }}">
                    {{ str_replace('_', ' ', ucfirst($task->status)) }}
                </span>
                <span class="flex-1 text-sm text-gray-800 truncate {{ $task->status === 'completed' ? 'line-through text-gray-400' : '' }}">
                    {{ $task->title }}
                </span>
                @if($task->due_date)
                <span class="text-xs {{ $task->isOverdue() ? 'text-red-500 font-medium' : 'text-gray-400' }}">
                    {{ $task->due_date->format('M d') }}
                </span>
                @endif
                <a href="{{ route('tasks.edit', $task) }}" class="text-gray-400 hover:text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
            </li>
            @endforeach
        </ul>
        @endif
    </div>

</div>
@endsection