<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RadarController;
use App\Http\Controllers\ChatController;

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

// Route::post('/matches', [RadarController::class, 'matches'])->name('matches');

// Route::get('/radar', [RadarController::class, 'index'])->name('radar.index');

Route::middleware('web')->group(function () {
    Route::get('/radar', [RadarController::class, 'index'])->name('radar.index');
    Route::post('/radar/matches', [RadarController::class, 'matches'])->name('radar.matches');
});

Route::get('/chat/{candidateId}', [ChatController::class, 'show'])->name('chat.show');


require __DIR__.'/auth.php';
