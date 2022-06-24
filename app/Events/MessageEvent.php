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

    public $name;
    public $chat_room_id;
    public $message;

    public function __construct($name, $chat_room_id, $message)
    {
        $this->name = $name;
        $this->chat_room_id = $chat_room_id;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return [$this->chat_room_id];
    }

    public function broadcastAs() {
        return 'message';
    }
}
