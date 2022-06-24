<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageEvent;
use App\Http\Resources\MessageResource;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function message(Request $request) {
        $validated = $request->validate([
            'chat_room_id' => 'required',
            'message' => 'required',
        ]);

        $chatRoom = ChatRoom::where('id', $validated['chat_room_id'])
            ->where('user1_id', auth()->user()->id)
            ->orWhere('user2_id', auth()->user()->id)->first();

        if (!$chatRoom) {
            return response()->json([
                'error' => [
                    'data' => 'Nevar nosūtīt ziņu svešā chat room.',
                ]
            ]);
        } else {
            event(new MessageEvent(auth()->user()->name, $validated['chat_room_id'], $validated['message']));

            $savedMessage = Message::create([
                'user_id' => auth()->user()->id,
                'chat_room_id' => $validated['chat_room_id'],
                'message' => $request->input('message'),
            ]);

            return response()->json([
                'Success' => 'Ziņa tika nosūtīta!',
                'message' => new MessageResource($savedMessage),
            ]);
        }
    }

    public function chatRoomMessages(Request $request) {
        $validated = $request->validate([
            'chat_room_id' => 'required',
        ]);


        $chatRoomMessages = Message::where('chat_room_id', $validated['chat_room_id'])
            ->paginate(($pag = $request->pagination) ? $pag : 10);


        return MessageResource::collection($chatRoomMessages);
    }
}
