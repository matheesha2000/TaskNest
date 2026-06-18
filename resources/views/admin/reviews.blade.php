@extends('layouts.admin')
@section('title', 'Review Moderation')
@section('subtitle', 'Monitor and manage user reviews')

@section('content')
<div class="space-y-5">

    {{-- ── Rating Overview ──────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Avg rating big card --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-8 h-8 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            </div>
            <div>
                <p class="text-4xl font-bold text-slate-800">{{ $avgRating }}</p>
                <p class="text-sm text-slate-400">Average Rating</p>
                <p class="text-xs text-slate-400 mt-0.5">from {{ $reviews->total() }} reviews</p>
            </div>
        </div>

        {{-- Rating breakdown bars --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6">
            <h3 class="text-sm font-semibold text-slate-700 mb-4">Rating Breakdown</h3>
            <div class="space-y-2">
                @foreach([5,4,3,2,1] as $star)
                    @php
                        $count  = $ratingBreakdown[$star] ?? 0;
                        $total  = max($reviews->total(), 1);
                        $width  = round($count / $total * 100);
                    @endphp
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1 w-16 flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-xs text-slate-600 font-medium">{{ $star }}</span>
                        </div>
                        <div class="flex-1 bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="h-2 rounded-full bg-amber-400 transition-all" style="width: {{ $width }}%"></div>
                        </div>
                        <span class="text-xs text-slate-400 w-8 text-right">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── Filter Bar ───────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4">
        <form method="GET" action="{{ route('admin.reviews') }}" class="flex items-center gap-3">
            <span class="text-sm text-slate-500 font-medium">Filter by rating:</span>
            @foreach([5,4,3,2,1] as $star)
                <a href="{{ route('admin.reviews', ['rating' => $star]) }}"
                   class="px-3 py-1.5 rounded-xl text-sm font-medium transition-colors
                          {{ request('rating') == $star
                              ? 'bg-amber-400 text-white'
                              : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    {{ $star }} ★
                </a>
            @endforeach
            @if(request('rating'))
                <a href="{{ route('admin.reviews') }}" class="text-sm text-slate-400 hover:text-slate-600 ml-1">Clear</a>
            @endif
            <span class="ml-auto text-xs text-slate-400">{{ $reviews->total() }} reviews</span>
        </form>
    </div>

    {{-- ── Reviews List ─────────────────────────────────────────────────── --}}
    <div class="space-y-3">
        @forelse($reviews as $review)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:border-slate-300 transition-colors">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3 flex-1 min-w-0">

                        {{-- Avatar --}}
                        <div class="w-9 h-9 rounded-full bg-brand-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                            {{ strtoupper(substr($review->user->name ?? '?', 0, 1)) }}
                        </div>

                        <div class="flex-1 min-w-0">
                            {{-- User + Stars --}}
                            <div class="flex items-center gap-3 mb-1 flex-wrap">
                                <span class="font-semibold text-slate-800 text-sm">{{ $review->user->name ?? 'Deleted User' }}</span>
                                <span class="text-xs text-slate-400">{{ $review->user->email ?? '' }}</span>
                                <div class="flex items-center gap-0.5 ml-auto">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </div>

                            {{-- Comment --}}
                            <p class="text-sm text-slate-600 leading-relaxed">{{ $review->comment }}</p>
                            <p class="text-xs text-slate-400 mt-2">{{ $review->created_at->format('M d, Y · g:i A') }} · {{ $review->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    {{-- Delete --}}
                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                          onsubmit="return confirm('Remove this review?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Remove
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400">
                No reviews found.
            </div>
        @endforelse
    </div>

    @if($reviews->hasPages())
        <div>{{ $reviews->links() }}</div>
    @endif

</div>
@endsection