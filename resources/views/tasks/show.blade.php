@extends('layouts.app')
@section('title', $task->title)

@section('header-actions')
    <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center gap-2 border border-gray-200 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
        Edit Task
    </a>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-5">

        {{-- Title & status --}}
        <div class="flex items-start justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-900 {{ $task->status === 'completed' ? 'line-through text-gray-400' : '' }}">
                {{ $task->title }}
            </h2>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium flex-shrink-0 {{ $task->statusBadgeClass() }}">
                {{ str_replace('_', ' ', ucfirst($task->status)) }}
            </span>
        </div>

        @if($task->description)
        <p class="text-gray-600 text-sm leading-relaxed">{{ $task->description }}</p>
        @endif

        <dl class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-4">
            @if(auth()->user()->isPro())
            <div>
                <dt class="text-xs font-medium text-gray-400 uppercase tracking-wider">Priority</dt>
                <dd class="mt-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $task->priorityBadgeClass() }}">
                        {{ ucfirst($task->priority) }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-400 uppercase tracking-wider">Category</dt>
                <dd class="mt-1 text-sm text-gray-700">{{ $task->category ?? '—' }}</dd>
            </div>
            @endif
            <div>
                <dt class="text-xs font-medium text-gray-400 uppercase tracking-wider">Due Date</dt>
                <dd class="mt-1 text-sm {{ $task->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-700' }}">
                    {{ $task->due_date ? $task->due_date->format('F j, Y') : '—' }}
                    @if($task->isOverdue()) <span class="text-red-400 text-xs">(overdue)</span> @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-400 uppercase tracking-wider">Created</dt>
                <dd class="mt-1 text-sm text-gray-700">{{ $task->created_at->format('F j, Y') }}</dd>
            </div>
        </dl>

        {{-- Quick status change --}}
        <div class="border-t border-gray-100 pt-4">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-2">Update Status</p>
            <div class="flex gap-2">
                @foreach(['pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed'] as $val => $label)
                <form method="POST" action="{{ route('tasks.status', $task) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="{{ $val }}">
                    <button type="submit"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium border transition-colors {{ $task->status === $val ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-200 text-gray-600 hover:border-indigo-300 hover:text-indigo-600' }}">
                        {{ $label }}
                    </button>
                </form>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection