<?php

namespace App\Http\Controllers;

use Http;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;

class TmdbController extends Controller
{
    public function index()
    {
        return view('tmdb.index');
    }

    public function search(Request $request)
    {
        $query = $request->input('search');

        $response = Http::withToken(env('TMDB_TOKEN'))->get('https://api.themoviedb.org/3/search/movie', ['query' => $query, 'language' => 'es-ES',]);

        $movies = $response->json()['results'] ?? [];

        return view('tmdb.index', compact('movies')); // vuelvo a la vista tmdb.index
    }

    public function store(Request $request)
    {
        $tmdbId = $request->input('tmdb_id');

        if (Movie::where('tmdb_id', $tmdbId)->exists()) {
            return redirect()->route('movies.index')->with('info', 'Esa película ya la tienes en tu catálogo.');
        }

        // llamada a la API
        $response = Http::withToken(env('TMDB_TOKEN'))->get("https://api.themoviedb.org/3/movie/{$tmdbId}", ['language' => 'es-ES',]);

        if ($response->failed()) {
            return back()->with('error', 'Error al conectar con TMDB');
        }

        $data = $response->json();

        $newMovie = Movie::create([
            'tmdb_id'      => $data['id'],
            'title'        => $data['title'],
            'release_date' => $data['release_date'] ?? null,
            'overview'     => $data['overview'] ?? '',
            'runtime'      => $data['runtime'] ?? 0,
            'poster_path'  => $data['poster_path'] ? 'https://image.tmdb.org/t/p/w500'.$data['poster_path'] : null,
        ]);

        if (!empty($data['genres'])) {
            $genreIds = [];
            foreach ($data['genres'] as $apiGenre) {
                // si no existe el genero, lo crea
                $genre = Genre::firstOrCreate(['tmdb_id' => $apiGenre['id']], ['name' => $apiGenre['name']]);
                $genreIds[] = $genre->id;
            }

            $newMovie->genres()->sync($genreIds);
        }

        return redirect()->route('movies.index')->with('success', '¡Película importada correctamente!');
    }
}