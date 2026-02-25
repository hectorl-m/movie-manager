<?php

use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ControlPanelController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TmdbController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// nada mas te logeas que haya algo interesante.
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    $watchedCount = $user->movies()->wherePivot('status', 'watched')->count();
    $pendingCount = $user->movies()->wherePivot('status', 'pending')->count();
    $reviewsCount = $user->reviews()->count();

    $recentReviews = $user->reviews()->with('movie')->latest()->take(3)->get();

    return view('dashboard', compact('watchedCount', 'pendingCount', 'reviewsCount', 'recentReviews'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/controlPanel', [ControlPanelController::class, 'index'])->middleware(['auth', 'verified'])->name('controlPanel');

Route::middleware('auth')->group(function () {
    // Importar
    Route::get('/import', [TmdbController::class, 'index'])->name('tmdb.index');
    Route::get('/import/search', [TmdbController::class, 'search'])->name('tmdb.search');
    Route::post('/import/store', [TmdbController::class, 'store'])->name('tmdb.store');

    // Peliculas
    Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
    Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');
    Route::delete('/movies/{movie}', [MovieController::class, 'destroy'])->name('movies.destroy')->middleware('can:admin');
    Route::post('/movies/{movie}/list', [MovieController::class, 'toggleList'])->name('movies.list.toggle');
    Route::post('/movies/{movie}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Reviews
    Route::patch('/reviews/{review}/toggle-visibility', [ReviewController::class, 'toggleVisibility'])->name('reviews.toggleVisibility')->middleware('can:admin');

    // Colecciones
    Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
    Route::post('/collections', [CollectionController::class, 'store'])->name('collections.store');
    Route::delete('/collections/{collection}', [CollectionController::class, 'destroy'])->name('collections.destroy');
    Route::get('/collections/{collection}', [CollectionController::class, 'show'])->name('collections.show');
    Route::delete('/collections/{collection}/movies/{movie}', [CollectionController::class, 'removeMovie'])->name('collections.removeMovie');
    Route::post('/movies/{movie}/add-to-collection', [CollectionController::class, 'addMovie'])->name('collections.addMovie');
});

require __DIR__.'/auth.php';
