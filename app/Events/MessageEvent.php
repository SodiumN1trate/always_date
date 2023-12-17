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
use Illuminate\Support\Facades\Log;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageId;
    public $chat_room_id;

    public function __construct($messageId, $chat_room_id)
    {
        $this->messageId = $messageId;
        $this->chat_room_id = $chat_room_id;
    }

    public function broadcastOn()
    {
        Log::info($this->chat_room_id);
        return new PrivateChannel('chat.'.$this->chat_room_id);
    }
}
