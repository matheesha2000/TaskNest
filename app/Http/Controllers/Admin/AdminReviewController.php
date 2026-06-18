<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with('user');

        // Filter by rating
        if ($rating = $request->input('rating')) {
            $query->where('rating', $rating);
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        $ratingBreakdown = Review::selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->orderByDesc('rating')
            ->pluck('total', 'rating');

        $avgRating = round(Review::avg('rating') ?? 0, 1);

        return view('admin.reviews', compact('reviews', 'ratingBreakdown', 'avgRating'));
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review removed.');
    }
}