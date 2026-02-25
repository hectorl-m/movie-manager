<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Movie $movie)
    {
        // validaciones
        $request->validate([
            'rating' => 'required|integer|min:1|max:10',
            'content' => 'nullable|string|max:1000',
        ]);

        // el usuario ha reseñado la pelicula?
        $existingReview = $movie->reviews()->where('user_id', auth()->id())->first();

        if ($existingReview) {
            return back()->with('error', 'You have already rated this movie previously.');
        }

        $movie->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $request->input('rating'),
            'content' => $request->input('content'),
            'is_visible' => true,
        ]);

        return back()->with('success', 'Your review has been saved successfully!');
    }
    
    public function toggleVisibility(Review $review)
    {
        $review->is_visible = !$review->is_visible;
        $review->save();

        $state = $review->is_visible ? 'visible' : 'hidden';
        return back()->with('success', "The review is now {$state}.");
    }
}
