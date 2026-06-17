@extends('layouts.app')
@section('title', $task->title)

@section('header-actions')
    <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center gap-2 border border-slate-200 text-slate-700 hover:bg-slate-50 hover:text-slate-900 text-sm px-4 py-2 rounded-xl font-semibold transition-all">
        Edit Task
    </a>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="glass-card rounded-2xl border border-slate-200/60 shadow-sm p-6 space-y-6">

        {{-- Title & status --}}
        <div class="flex items-start justify-between gap-4">
            <h2 class="text-xl font-bold text-slate-900 leading-snug {{ $task->status === 'completed' ? 'line-through text-slate-400' : '' }}">
                {{ $task->title }}
            </h2>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold tracking-wide border flex-shrink-0 {{ $task->statusBadgeClass() }}">
                {{ str_replace('_', ' ', strtoupper($task->status)) }}
            </span>
        </div>

        @if($task->description)
        <p class="text-slate-600 text-sm leading-relaxed">{{ $task->description }}</p>
        @endif

        <dl class="grid grid-cols-2 gap-y-5 gap-x-4 border-t border-slate-200/60 pt-5">
            @if(auth()->user()->isPro())
            <div>
                <dt class="text-[10px] font-bold text-slate-450 uppercase tracking-wider">Priority</dt>
                <dd class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide border {{ $task->priorityBadgeClass() }}">
                        {{ strtoupper($task->priority) }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-[10px] font-bold text-slate-450 uppercase tracking-wider">Category</dt>
                <dd class="mt-1 text-sm text-slate-750">
                    @if($task->category)
                        <span class="bg-indigo-50 text-indigo-700 border border-indigo-100 px-2.5 py-0.5 rounded-lg text-[11px] font-semibold">
                            {{ $task->category }}
                        </span>
                    @else
                        <span class="text-slate-400 font-mono">—</span>
                    @endif
                </dd>
            </div>
            @endif
            <div>
                <dt class="text-[10px] font-bold text-slate-450 uppercase tracking-wider">Due Date</dt>
                <dd class="mt-1 text-sm font-semibold {{ $task->isOverdue() ? 'text-red-650 font-bold' : 'text-slate-700' }}">
                    {{ $task->due_date ? $task->due_date->format('F j, Y') : '—' }}
                    @if($task->isOverdue()) <span class="text-red-600 text-xs">(overdue)</span> @endif
                </dd>
            </div>
            <div>
                <dt class="text-[10px] font-bold text-slate-450 uppercase tracking-wider">Created</dt>
                <dd class="mt-1 text-sm text-slate-650">{{ $task->created_at->format('F j, Y') }}</dd>
            </div>
        </dl>

        {{-- Quick status change --}}
        <div class="border-t border-slate-200/60 pt-5">
            <p class="text-[10px] font-bold text-slate-450 uppercase tracking-wider mb-3">Update Status</p>
            <div class="flex flex-wrap gap-2">
                @foreach(['pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed'] as $val => $label)
                <form method="POST" action="{{ route('tasks.status', $task) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="{{ $val }}">
                    <button type="submit"
                            class="px-3.5 py-2 rounded-xl text-xs font-semibold border transition-all hover:scale-[1.02] active:scale-100 {{ $task->status === $val ? 'bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-500/15' : 'border-slate-200 text-slate-500 hover:border-slate-350 hover:text-slate-800 hover:bg-slate-50' }}">
                        {{ $label }}
                    </button>
                </form>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection