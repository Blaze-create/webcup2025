<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class ChatController extends Controller
{
    public function show(string $candidateId)
    {
        // candidateId is passed to the Blade view
        return view('chat.show', [
            'candidateId' => $candidateId,
        ]);
    }
    public function findchat($name)
    {
        $match = User::where('name', $name)->firstOrFail();
        $me = Auth::id();
        $other = $match->id;

        $messages = Message::where(function ($q) use ($me, $other) {
            $q->where('sender_id', $me)
                ->where('receiver_id', $other);
        })
            ->orWhere(function ($q) use ($me, $other) {
                $q->where('sender_id', $other)
                    ->where('receiver_id', $me);
            })
            ->orderBy('created_at', 'asc')
            ->get();


        return view('chats.view', compact('messages'));
    }
}
