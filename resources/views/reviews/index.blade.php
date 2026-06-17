@extends('layouts.app')
@section('title', 'Reviews')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    {{-- Submit / Edit Review --}}
    <div class="glass-card rounded-2xl border border-slate-200/60 p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800 mb-5">
            {{ $myReview ? 'Your Review' : 'Write a Review' }}
        </h2>

        @if($myReview)
            {{-- Display my existing review with edit option --}}
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5 mb-5 shadow-inner">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $myReview->rating ? 'text-yellow-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                        <span class="ml-2 text-xs font-semibold text-slate-500">({{ $myReview->rating }}/5)</span>
                    </div>
                    <span class="text-xs text-slate-500">{{ $myReview->updated_at->diffForHumans() }}</span>
                </div>
                <p class="text-slate-700 text-sm leading-relaxed">{{ $myReview->comment }}</p>
            </div>

            <div class="flex gap-3">
                <button onclick="document.getElementById('review-form').classList.toggle('hidden')"
                        class="px-4 py-2 bg-slate-100 text-slate-700 border border-slate-200 rounded-xl text-sm font-semibold hover:bg-slate-200 transition-all cursor-pointer">
                    Edit Review
                </button>
                <form method="POST" action="{{ route('reviews.destroy', $myReview) }}"
                      onsubmit="return confirm('Remove your review?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 border border-red-100 rounded-xl text-sm font-semibold hover:bg-red-100 transition-all cursor-pointer">
                        Remove
                    </button>
                </form>
            </div>
        @endif

        <div id="review-form" class="{{ $myReview ? 'hidden mt-5' : '' }}">
            <form method="POST" action="{{ route('reviews.store') }}" class="space-y-5">
                @csrf

                {{-- Star rating picker --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Rating</label>
                    <div class="flex items-center gap-2" id="star-picker">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" data-value="{{ $i }}"
                                    class="star-btn w-9 h-9 text-2xl transition-transform hover:scale-110 focus:outline-none text-slate-300"
                                    onclick="setRating({{ $i }})">
                                ☆
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="{{ old('rating', $myReview?->rating ?? '') }}">
                    @error('rating')
                        <p class="text-red-650 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="comment" class="block text-sm font-semibold text-slate-700 mb-1.5">Comment</label>
                    <textarea name="comment" id="comment" rows="4"
                              class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/15 transition-all resize-none"
                              placeholder="Share your experience with TaskNest...">{{ old('comment', $myReview?->comment ?? '') }}</textarea>
                    @error('comment')
                        <p class="text-red-650 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all cursor-pointer">
                    Save Review
                </button>
            </form>
        </div>
    </div>

    {{-- All Reviews --}}
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-slate-850 tracking-tight">Community Reviews</h3>

        @if($reviews->isEmpty())
            <div class="glass-card rounded-2xl border border-slate-200/60 p-12 text-center text-slate-500">
                No reviews yet. Be the first to share your experience!
            </div>
        @else
            <div class="space-y-4">
                @foreach($reviews as $review)
                    <div class="glass-card rounded-2xl border border-slate-200/60 p-5 shadow-sm relative">
                        <div class="flex items-start justify-between mb-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-indigo-55 border border-indigo-100 flex items-center justify-center text-indigo-600 text-sm font-bold">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 leading-none">{{ $review->user->name }}</p>
                                    <p class="text-[11px] text-slate-500 mt-1">{{ $review->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $review->comment }}</p>
                    </div>
                @endforeach
            </div>

            @if($reviews->hasPages())
                <div class="mt-4">{{ $reviews->links() }}</div>
            @endif
        @endif
    </div>
</div>

<script>
function setRating(value) {
    document.getElementById('rating-input').value = value;
    const btns = document.querySelectorAll('.star-btn');
    btns.forEach((btn, idx) => {
        btn.textContent = idx < value ? '★' : '☆';
        btn.style.color = idx < value ? '#facc15' : '#cbd5e1';
    });
}

// Initialise stars on load
const saved = parseInt(document.getElementById('rating-input').value);
if (saved) setRating(saved);
</script>
@endsection