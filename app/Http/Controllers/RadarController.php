<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Services\AiMatchService;

use App\Models\Like;

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



    
}
