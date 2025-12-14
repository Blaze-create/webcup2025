<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\MatchModel;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function like(Request $request)
    {
        $request->validate(['liked_id' => 'required|exists:users,id']);
        $me = auth()->id();
        $them = (int) $request->liked_id;

        if ($me === $them) {
            return response()->json(['ok' => false, 'message' => 'Cannot like yourself'], 422);
        }

        // 1) save like (ignore if already liked)
        Like::firstOrCreate([
            'liker_id' => $me,
            'liked_id' => $them,
        ]);

        // 2) check reverse like
        $reverse = Like::where('liker_id', $them)->where('liked_id', $me)->exists();

        $matched = false;
        $matchId = null;

        if ($reverse) {
            // normalize pair order
            $a = min($me, $them);
            $b = max($me, $them);

            $match = MatchModel::firstOrCreate([
                'user_one_id' => $a,
                'user_two_id' => $b,
            ]);

            $matched = true;
            $matchId = $match->id;
        }

        return response()->json([
            'ok' => true,
            'matched' => $matched,
            'match_id' => $matchId,
        ]);
    }

    public function myMatches()
    {
        $me = auth()->id();

        // matches where I'm either side
        $matches = MatchModel::where('user_one_id', $me)
            ->orWhere('user_two_id', $me)
            ->latest()
            ->get()
            ->map(function ($m) use ($me) {
                $otherId = ($m->user_one_id === $me) ? $m->user_two_id : $m->user_one_id;
                $other = \App\Models\User::select('id','name','email')->find($otherId);

                return [
                    'id' => $m->id,
                    'other' => $other,
                    'created_at' => $m->created_at,
                ];
            });

        return response()->json(['ok' => true, 'matches' => $matches]);
    }
}
