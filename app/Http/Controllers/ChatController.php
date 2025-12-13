<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
        public function show(string $candidateId)
    {
        // candidateId is passed to the Blade view
        return view('chat.show', [
            'candidateId' => $candidateId,
        ]);
    }
}
