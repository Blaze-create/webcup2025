<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Services\AiMatchService;

use App\Models\Like;
use App\Models\User;

class RadarController extends Controller
{
    public function index()
    {
        return view('radar.index');
    }

    public function matches(Request $request, AiMatchService $ai)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:80',
            'species' => 'required|string|max:40',
            'atmosphere' => 'required|string|max:40',
            'gravity' => 'required|string|max:40',
            'tempMin' => 'required|integer|min:-300|max:500',
            'tempMax' => 'required|integer|min:-300|max:500',
            'comms' => 'required|string|max:40',
            'intent' => 'required|string|max:40',
            'bioType' => 'required|string|max:40',
            'risk' => 'required|integer|min:0|max:100',
        ]);

        // Save/update my profile
        if (Auth::check()) {
            Profile::updateOrCreate(
                ['user_id' => Auth::id()],
                array_merge($data, ['user_id' => Auth::id()])
            );
        }

        $candidatesQuery = Profile::query()->limit(50);

        if (Auth::check()) {
            $me = Auth::id();

            // all users I already liked
            $likedIds = Like::where('liker_id', $me)->pluck('liked_id');

            $candidatesQuery
                ->where('user_id', '!=', $me)                  // not me
                ->whereNotIn('user_id', $likedIds);            // not already liked
        }

        $candidates = $candidatesQuery->get(); // Collection

        // ✅ NO CANDIDATES
        if ($candidates->isEmpty()) {
            return response()->json([
                'ok' => true,
                'count' => 0,
                'results' => [],
                'message' => 'No candidates available yet. Ask an admin to seed more profiles/users.',
            ]);
        }

        $results = $ai->rankMatches($data, $candidates->all()); // array passed to service

        return response()->json([
            'ok' => true,
            'count' => count($results),
            'results' => $results,
        ]);
    }


    public function likesCount()
    {
        if (!Auth::check()) {
            return response()->json([
                'ok' => false,
                'count' => 0,
            ]);
        }

        $count = Like::where('liker_id', Auth::id())->count();

        return response()->json([
            'ok' => true,
            'count' => $count,
        ]);
    }

    public function mutualLikes()
    {
        $me = Auth::id();

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

        // ✅ how many likes the current user received (not necessarily mutual)

        return view('match.matches', compact('matches'));
    }
    public function likepage()
    {
        $me = Auth::id();

        // ✅ IDs of users who mutually like each other
        $matchedIds = Like::query()
            ->from('likes as l1')
            ->join('likes as l2', function ($join) {
                $join->on('l1.liked_id', '=', 'l2.liker_id')
                    ->on('l1.liker_id', '=', 'l2.liked_id');
            })
            ->where('l1.liker_id', $me)
            ->distinct()
            ->pluck('l1.liked_id');

        // ✅ Full user records for matches
        $matches = User::whereIn('id', $matchedIds)->get();
        $likesCount = Like::where('liked_id', $me)->count();
        $matchesCount = $matchedIds->count();
        return view('match.match', compact('likesCount', 'matchesCount'));
    }
}
