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

    {{-- Stats cards Bento Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        @foreach([
            [
                'label' => 'Total Tasks',
                'value' => $stats['total'],
                'color' => 'indigo',
                'textClass' => 'text-indigo-600',
                'bgClass' => 'bg-indigo-50 border-indigo-100',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>'
            ],
            [
                'label' => 'Pending',
                'value' => $stats['pending'],
                'color' => 'yellow',
                'textClass' => 'text-amber-600',
                'bgClass' => 'bg-amber-50 border-amber-200/50',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            ],
            [
                'label' => 'In Progress',
                'value' => $stats['in_progress'],
                'color' => 'blue',
                'textClass' => 'text-blue-600',
                'bgClass' => 'bg-blue-50 border-blue-200/50',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>'
            ],
            [
                'label' => 'Completed',
                'value' => $stats['completed'],
                'color' => 'green',
                'textClass' => 'text-emerald-600',
                'bgClass' => 'bg-emerald-50 border-emerald-200/50',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            ],
        ] as $card)
        <div class="glass-card glass-card-hover rounded-2xl p-6 relative overflow-hidden flex flex-col justify-between shadow-sm">
            {{-- Glowing radial shadow behind the icon --}}
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br from-indigo-500/5 to-purple-500/5 rounded-full blur-xl pointer-events-none"></div>
            
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ $card['label'] }}</span>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $card['bgClass'] }} {{ $card['textClass'] }}">
                    {!! $card['icon'] !!}
                </div>
            </div>
            
            <div>
                <p class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">{{ $card['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Overdue alert (Pro only) --}}
    @if(auth()->user()->isPro() && $overdueTasks->count() > 0)
    <div class="bg-red-50 border border-red-200/60 rounded-2xl p-5 flex items-start gap-3.5 shadow-sm">
        <div class="p-1.5 bg-red-100 text-red-600 rounded-lg">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
            <p class="text-sm font-bold text-red-700">{{ $overdueTasks->count() }} overdue task{{ $overdueTasks->count() > 1 ? 's' : '' }}</p>
            <p class="text-xs text-slate-500 mt-1">{{ $overdueTasks->pluck('title')->implode(', ') }}</p>
        </div>
    </div>
    @endif

    {{-- Free plan upgrade nudge --}}
    @if(!auth()->user()->isPro())
    <div class="bg-gradient-to-r from-indigo-600/90 via-violet-600/90 to-purple-700/90 border border-indigo-500/20 rounded-2xl p-6 text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-xl relative overflow-hidden backdrop-blur-sm">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.1),transparent)] pointer-events-none"></div>
        <div class="relative z-10">
            <p class="font-extrabold text-lg tracking-tight">Upgrade to TaskNest Pro ⭐</p>
            <p class="text-sm text-indigo-100 mt-1 max-w-xl">Unlock unlimited tasks, customizable priorities, categories, and advanced dashboard analytics.</p>
        </div>
        <a href="{{ route('subscription.index') }}" class="relative z-10 bg-white text-indigo-900 text-sm font-bold px-5 py-3 rounded-xl hover:bg-slate-100 hover:shadow-lg hover:scale-[1.02] active:scale-100 transition-all flex-shrink-0">
            Upgrade — $9.99/mo
        </a>
    </div>
    @endif

    {{-- Recent tasks --}}
    <div class="glass-card rounded-2xl shadow-sm overflow-hidden border border-slate-200/60">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200/60 bg-slate-50">
            <h2 class="font-bold text-slate-800 tracking-tight">Recent Tasks</h2>
            <a href="{{ route('tasks.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold transition-colors">View all</a>
        </div>

        @if($recentTasks->isEmpty())
        <div class="py-16 text-center">
            <p class="text-slate-500 text-sm">No tasks yet.</p>
            <a href="{{ route('tasks.create') }}" class="mt-3 inline-block text-sm text-indigo-600 hover:underline">Create your first task →</a>
        </div>
        @else
        <ul class="divide-y divide-slate-100">
            @foreach($recentTasks as $task)
            <li class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50/70 transition-all duration-150">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide {{ $task->statusBadgeClass() }}">
                    {{ str_replace('_', ' ', strtoupper($task->status)) }}
                </span>
                <span class="flex-1 text-sm text-slate-700 truncate {{ $task->status === 'completed' ? 'line-through text-slate-400' : '' }}">
                    {{ $task->title }}
                </span>
                @if($task->due_date)
                <span class="text-xs {{ $task->isOverdue() ? 'text-red-600 font-semibold' : 'text-slate-500' }}">
                    {{ $task->due_date->format('M d') }}
                </span>
                @endif
                <a href="{{ route('tasks.edit', $task) }}" class="text-slate-400 hover:text-indigo-600 p-1.5 rounded-lg hover:bg-slate-100 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
            </li>
            @endforeach
        </ul>
        @endif
    </div>

</div>
@endsection