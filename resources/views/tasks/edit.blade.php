@extends('layouts.app')
@section('title', 'Edit Task')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-card rounded-2xl border border-slate-200/60 shadow-sm p-6">
        <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            {{-- Title --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Title <span class="text-red-650">*</span></label>
                <input type="text" name="title" value="{{ old('title', $task->title) }}"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 transition-all @error('title') border-red-500 @enderror"
                       autofocus>
                @error('title') <p class="text-red-650 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Description</label>
                <textarea name="description" rows="4"
                          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 transition-all resize-none">{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                    <select name="status" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 transition-all">
                        @foreach(['pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $task->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                           class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 transition-all">
                    @error('due_date') <p class="text-red-650 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            @if(auth()->user()->isPro())
            <div class="border-t border-slate-200/60 pt-5">
                <p class="text-xs font-bold text-indigo-650 uppercase tracking-wider mb-4">Pro Customization</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Priority</label>
                        <select name="priority" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 transition-all">
                            @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $val => $label)
                            <option value="{{ $val }}" {{ old('priority', $task->priority) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Category</label>
                        <input type="text" name="category" value="{{ old('category', $task->category) }}"
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 transition-all"
                               placeholder="e.g. Work, Personal, Side project">
                    </div>
                </div>
            </div>
            @endif

            <div class="flex items-center gap-4 pt-4 border-t border-slate-200/60">
                <button type="submit" class="bg-indigo-600 text-white font-semibold px-6 py-2.5 rounded-xl text-sm hover:bg-indigo-700 hover:shadow-md hover:scale-[1.01] active:scale-100 transition-all">
                    Save Changes
                </button>
                <a href="{{ route('tasks.index') }}" class="text-sm text-slate-550 hover:text-slate-800 font-semibold transition-colors">Cancel</a>

                {{-- Danger zone trigger button --}}
                <button type="submit" form="delete-task-form" class="text-sm text-red-600 hover:text-red-750 font-semibold ml-auto hover:underline" onclick="return confirm('Delete this task from your Nest?')">
                    Delete
                </button>
            </div>
        </form>

        {{-- Danger zone form (outside the main form to prevent nesting) --}}
        <form id="delete-task-form" method="POST" action="{{ route('tasks.destroy', $task) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection