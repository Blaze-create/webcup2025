<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MatchModel;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Like;


class ChatController extends Controller
{
    public function show(User $user)
    {
        $me = Auth::id();
        $receiver = User::findOrFail($user->id);

        // 🔒 SECURITY: only allow chat if mutual like exists
        $isMatch =
            Like::where('liker_id', $me)->where('liked_id', $user->id)->exists() &&
            Like::where('liker_id', $user->id)->where('liked_id', $me)->exists();

        abort_unless($isMatch, 403);

        // Load conversation
        $messages = Message::where(function ($q) use ($me, $user) {
            $q->where('sender_id', $me)->where('receiver_id', $user->id);
        })
            ->orWhere(function ($q) use ($me, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $me);
            })
            ->orderBy('created_at')
            ->get();

        return view('match.show', compact('user', 'messages', 'receiver'));
    }

    public function send(Request $request, User $user)
    {
        $me = Auth::id();

        // 🔒 SECURITY: allow only mutual matches
        $isMatch =
            Like::where('liker_id', $me)->where('liked_id', $user->id)->exists() &&
            Like::where('liker_id', $user->id)->where('liked_id', $me)->exists();

        abort_unless($isMatch, 403);

        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        Message::create([
            'sender_id'   => $me,
            'receiver_id' => $user->id,
            'body'        => $request->body,
        ]);

        // Redirect back to chat
        return redirect()->route('chat.show', $user->id);
    }
}
