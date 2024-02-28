<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model {
    use HasFactory;

    protected $fillable = [
        'user1_id',
        'user2_id',
    ];

    public function isUserIn() {
        if ($this->user1_id === auth()->user()->id) {
            return true;
        }

        if ($this->user2_id === auth()->user()->id) {
            return true;
        }

        return false;
    }
}
