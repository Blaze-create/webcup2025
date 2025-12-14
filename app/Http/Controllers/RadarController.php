<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Services\AiMatchService;

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

        // Optional: if logged in, save/update the user's profile
        if (Auth::check()) {
            Profile::updateOrCreate(
                ['user_id' => Auth::id()],
                array_merge($data, ['user_id' => Auth::id()])
            );
        }

        // Fetch candidates from DB (other users), fallback to seeded demo if none
        $candidates = Profile::query()
            ->when(Auth::check(), fn($q) => $q->where('user_id', '!=', Auth::id()))
            ->limit(50)
            ->get()
            ->all();

        $results = $ai->rankMatches($data, $candidates);

        return response()->json([
            'ok' => true,
            'count' => count($results),
            'results' => $results,
        ]);
    }



    
}
