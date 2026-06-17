<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $myReview = Review::where('user_id', Auth::id())->first();
        $reviews  = Review::with('user')->latest()->paginate(10);

        return view('reviews.index', compact('myReview', 'reviews'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'rating'  => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return redirect()->route('reviews.index')->with('success', 'Your review has been submitted. Thank you!');
    }

    public function destroy()
    {
        Review::where('user_id', Auth::id())->delete();

        return redirect()->route('reviews.index')->with('success', 'Your review has been removed.');
    }
}