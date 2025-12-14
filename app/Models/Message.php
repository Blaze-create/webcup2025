<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['match_id','sender_id','content'];

    public function match() {
        return $this->belongsTo(MatchModel::class, 'match_id');
    }
}
