<?php

namespace App\Events;

use GuzzleHttp\Psr7\Request;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $chat_room_id;
    public $message;

    public function __construct($user_id, $chat_room_id, $message)
    {
        $this->chat_room_id = $chat_room_id;
        $this->user_id = $user_id;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel.'.$this->chat_room_id);
    }
}
