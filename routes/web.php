<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RadarController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MatchController;

use App\Http\Controllers\MatchesController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');





    //chat
       // like action
    // Route::post('/like', [MatchController::class, 'like'])->name('like');

    // fetch my matches
    // Route::get('/matches-data', [MatchController::class, 'myMatches'])->name('matches.data');

    // chat messages
    // Route::get('/matches/{match}/messages', [ChatController::class, 'messages'])->name('chat.messages');
    // Route::post('/matches/{match}/messages', [ChatController::class, 'send'])->name('chat.send');

    // pages (Blade views)
    // Route::view('/match', 'match.index')->name('match.page');
    // Route::view('/matches', 'matches.index')->name('matches.page');
    // Route::view('/chat/{match}', 'chat.index')->name('chat.page');

    //new fixed
    Route::get('/match/{id}', function ($id) {
        return view('match.show', ['id' => $id]);
    })->name('match.show');



});

Route::view('/match', 'match.match')->name('match.page');

Route::get('/matches',[RadarController::class,'mutualLikes'])->name('matches.page');

//for liking
Route::middleware(['auth'])->post('/like', [MatchController::class, 'like'])->name('like');

//fetching all liking of one user
Route::middleware('auth')->get(
    '/match/likes-count',
    [RadarController::class, 'likesCount']
)->name('match.likes.count');


// Route::post('/matches', [RadarController::class, 'matches'])->name('matches');

// Route::get('/radar', [RadarController::class, 'index'])->name('radar.index');

Route::middleware('web')->group(function () {
    Route::get('/radar', [RadarController::class, 'index'])->name('radar.index');
    Route::post('/radar/matches', [RadarController::class, 'matches'])->name('radar.matches');
});


Route::view('/matches', 'match.matches')->name('matches.page');
//MatchsController to text
Route::middleware('auth')->group(function () {
    Route::view('/matches', 'match.matches')->name('matches.page');

    Route::get('/matches/data', [MatchesController::class, 'data'])->name('matches.data');
    Route::get('/matches/notifications', [MatchesController::class, 'notifications'])->name('matches.notifications');

    Route::post('/matches/requests/{id}/accept', [MatchesController::class, 'accept'])->name('matches.accept');
    Route::post('/matches/requests/{id}/decline', [MatchesController::class, 'decline'])->name('matches.decline');
});

// Route::get('chats/chat',[RadarController::class,'matches'])->name('chat.matches');

Route::get('/chat/{name}',[ChatController::class,'findchat'])->name('find.chat');
Route::post('/chat/{name}/send',[ChatController::class,'send'])->name('chat.send');

require __DIR__.'/auth.php';
