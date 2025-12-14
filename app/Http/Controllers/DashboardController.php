<?php

// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Like;

class DashboardController extends Controller
{
    // public function index()
    // {

    //     $user = auth()->user()->load('profile');
    //     return view('dashboardnew', compact('user'));
    // }
      public function index()
    {
        $me = Auth::id();

        $user = Auth::user()->load('profile');

        // People I liked
        $likedIds = Like::where('liker_id', $me)->pluck('liked_id')->unique();
        $likedUsers = User::whereIn('id', $likedIds)->get();

        // Matches (mutual likes)
        $matchedIds = Like::query()
            ->from('likes as l1')
            ->join('likes as l2', function ($join) {
                $join->on('l1.liked_id', '=', 'l2.liker_id')
                    ->on('l1.liker_id', '=', 'l2.liked_id');
            })
            ->where('l1.liker_id', $me)
            ->distinct()
            ->pluck('l1.liked_id');

        $matches = User::whereIn('id', $matchedIds)->get();

        return view('dashboardnew', compact('user', 'likedUsers', 'matches'));
    }
}