<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MatchModel;
use App\Models\Message;


class ChatController extends Controller
{
    //     public function show(string $candidateId)
    // {
    //     // candidateId is passed to the Blade view
    //     return view('chat.show', [
    //         'candidateId' => $candidateId,
    //     ]);
    //}
      private function assertUserOwnsMatch(MatchModel $match)
    {
        $me = auth()->id();
        if ($match->user_one_id !== $me && $match->user_two_id !== $me) {
            abort(403);
        }
    }

    public function messages(MatchModel $match)
    {
        $this->assertUserOwnsMatch($match);

        $msgs = Message::where('match_id', $match->id)
            ->orderBy('created_at', 'asc')
            ->get(['id','sender_id','content','created_at']);

        return response()->json(['ok' => true, 'messages' => $msgs]);
    }

    public function send(Request $request, MatchModel $match)
    {
        $this->assertUserOwnsMatch($match);

        $data = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $msg = Message::create([
            'match_id' => $match->id,
            'sender_id' => auth()->id(),
            'content' => $data['content'],
        ]);

        return response()->json(['ok' => true, 'message' => $msg]);
    }
}
