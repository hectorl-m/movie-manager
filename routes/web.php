<?php

use App\Http\Controllers\ControlPanelController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TmdbController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/controlPanel', [ControlPanelController::class, 'index'])->middleware(['auth', 'verified'])->name('controlPanel');

Route::middleware('auth')->group(function () {
    // Rutas de Importación
    Route::get('/import', [TmdbController::class, 'index'])->name('tmdb.index');
    Route::get('/import/search', [TmdbController::class, 'search'])->name('tmdb.search');
    Route::post('/import/store', [TmdbController::class, 'store'])->name('tmdb.store');

    Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
    Route::post('/movies/{movie}/list', [MovieController::class, 'toggleList'])->name('movies.list.toggle');
});

require __DIR__.'/auth.php';
