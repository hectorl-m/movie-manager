<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::with(['users' => function($q) {
            $q->where('users.id', auth()->id());
        }]);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('year')) {
            $query->whereYear('release_date', $request->year);
        }

        // relacion N:M
        if ($request->filled('genre')) {
            $query->whereHas('genres', function($q) use ($request) {
                $q->where('genres.id', $request->genre);
            });
        }

        $sort = $request->get('sort', 'created_at'); // ordenado por fecha de añadido
        $direction = $request->get('direction', 'desc');

        switch ($sort) {
            case 'title':
                $query->orderBy('title', $direction);
                break;
            case 'year':
                $query->orderBy('release_date', $direction);
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // withQueryString() para que al cambiar de página no se pierdan los filtros aplicados
        $movies = $query->paginate(12)->withQueryString();

        $genres = Genre::orderBy('name')->get();

        return view('movies.index', compact('movies', 'genres'));
    }

    public function show(Movie $movie)
    {
        $movie->load(['genres', 'reviews.user']);
        
        $averageRating = $movie->reviews()->avg('rating');
        $collections = auth()->user()->collections()->orderBy('name')->get();

        return view('movies.show', compact('movie', 'averageRating', 'collections'));
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();

        return back()->with('success', 'Movie successfully removed from catalog.');
    }
    
    public function toggleList(Request $request, Movie $movie)
    {
        $status = $request->input('status');
        $user = auth()->user();

        $existing = $user->movies()->where('movie_id', $movie->id)->first();

        if ($existing && $existing->pivot->status === $status) {
            $user->movies()->detach($movie->id);
            $mensaje = 'Movie removed from your list.';
        } else {
            $user->movies()->syncWithoutDetaching([
                $movie->id => ['status' => $status]
            ]);
            $mensaje = $status === 'pending' ? 'Added to Pending.' : 'Marked as Viewed.';
        }

        return back()->with('success', $mensaje);
    }
}