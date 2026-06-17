@extends('layouts.app')
@section('title', 'Create Task')

@section('content')
<div class="max-w-2xl">
    <div class="glass-card rounded-2xl border border-slate-200/60 shadow-sm p-6">
        <form method="POST" action="{{ route('tasks.store') }}" class="space-y-6">
            @csrf

            {{-- Title --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Title <span class="text-red-650">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 transition-all @error('title') border-red-500 @enderror"
                       placeholder="What needs to be done?" autofocus>
                @error('title') <p class="text-red-650 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Description</label>
                <textarea name="description" rows="4"
                          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 transition-all resize-none"
                          placeholder="Provide details about the task..."></textarea>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                <select name="status" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 transition-all">
                    <option value="pending"     {{ old('status') === 'pending'     ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed"   {{ old('status') === 'completed'   ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            {{-- Due Date --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Due Date</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}"
                       min="{{ date('Y-m-d') }}"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 transition-all">
                @error('due_date') <p class="text-red-650 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Pro-only fields --}}
            @if(auth()->user()->isPro())
            <div class="border-t border-slate-200/60 pt-5">
                <p class="text-xs font-bold text-indigo-650 uppercase tracking-wider mb-4">Pro Customization</p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Priority</label>
                        <select name="priority" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 transition-all">
                            <option value="low"    {{ old('priority') === 'low'    ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high"   {{ old('priority') === 'high'   ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Category</label>
                        <input type="text" name="category" value="{{ old('category') }}"
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 transition-all"
                               placeholder="e.g. Work, Personal, Side project">
                    </div>
                </div>
            </div>
            @else
            {{-- Upgrade nudge --}}
            <div class="rounded-xl border border-dashed border-indigo-250 bg-indigo-50/70 px-4 py-3 flex items-center gap-3">
                <svg class="w-5 h-5 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <p class="text-sm text-indigo-850">
                    Priority levels & categories require a <a href="{{ route('subscription.index') }}" class="font-bold underline text-indigo-600 hover:text-indigo-700">Pro plan</a>.
                </p>
            </div>
            @endif

            {{-- Actions --}}
            <div class="flex items-center gap-4 pt-2 border-t border-slate-200/60">
                <button type="submit" class="bg-indigo-600 text-white font-semibold px-6 py-2.5 rounded-xl text-sm hover:bg-indigo-700 hover:shadow-md hover:scale-[1.01] active:scale-100 transition-all">
                    Create Task
                </button>
                <a href="{{ route('tasks.index') }}" class="text-sm text-slate-500 hover:text-slate-800 font-semibold transition-colors">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection