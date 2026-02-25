<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Movie;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $myCollections = auth()->user()->collections()->latest()->get();
        
        $publicCollections = Collection::where('is_public', true)
            ->where('user_id', '!=', auth()->id())
            ->with('user')->latest()->get();
        
        return view('collections.index', compact('myCollections', 'publicCollections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        auth()->user()->collections()->create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'is_public' => $request->has('is_public'), 
        ]);

        return back()->with('success', 'Collection created successfully');
    }

    public function destroy(Collection $collection)
    {
        if ($collection->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action, You cannot delete other people collections.');
        }

        $collection->delete();

        return back()->with('success', 'Collection deleted successfully');
    }

    public function show(Collection $collection)
    {
        if (!$collection->is_public && $collection->user_id !== auth()->id()) {
            abort(403, 'This collection is private.');
        }

        // películas que están dentro de esta colección
        $collection->load('movies');

        return view('collections.show', compact('collection'));
    }

    public function removeMovie(Collection $collection, Movie $movie)
    {
        // para que solo el dueño pueda quitar películas
        if ($collection->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // desenlazar la película de la tabla pivote (collection_movie)
        $collection->movies()->detach($movie->id);

        return back()->with('success', 'Movie removed from the collection.');
    }

    public function addMovie(Request $request, Movie $movie)
    {
        $request->validate([
            'collection_id' => 'required|exists:collections,id',
        ]);

        $collection = auth()->user()->collections()->findOrFail($request->collection_id);

        // syncWithoutDetaching evita que se duplique si le das 2 veces
        $collection->movies()->syncWithoutDetaching($movie->id);

        return back()->with('success', 'Movie added to the collection successfully');
    }
}
