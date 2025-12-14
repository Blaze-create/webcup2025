<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Profile;
use App\Models\User;
use App\Models\ChatRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchesController extends Controller
{
   public function data()
    {
        $me = Auth::id();

        // IDs of users I liked
        $likedIds = Like::where('liker_id', $me)->pluck('liked_id');

        // Fetch ONLY usernames
        $users = User::whereIn('id', $likedIds)
            ->select('id', 'name')
            ->get()
            ->map(fn ($u) => [
                'id'   => $u->id,
                'name' => $u->name,
            ]);

        return response()->json([
            'ok' => true,
            'matches' => $users
        ]);
    }
}
