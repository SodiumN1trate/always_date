<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageEvent;
use App\Http\Resources\MessageResource;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MessageController extends Controller {
    /**
     * @OA\Post(
     *      path="/messages",
     *      operationId="postMessages",
     *      tags={"Message"},
     *      summary="Nosūta ziņu",
     *      description="Nosūta ziņu citam lietotājam",
     *      security={{ "bearer": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  required = {"chat_room_id", "message"},
     *                  @OA\Property(format="integer", description="Saraktes grupas id", property="chat_room_id"),
     *                  @OA\Property(format="string", description="Ziņa kura tiks nosūtita otram lietotājam", property="message"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/MessageResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Nevar nosūtīt ziņu svešā chat room.",
     *      )
     *)
     */
    public function message(Request $request) {
        $validated = $request->validate([
            'chat_room_id' => 'required|integer',
            'message' => 'required|max:255',
        ]);

        $chatRoom = ChatRoom::where('id', $validated['chat_room_id'])->first();
        if (!$chatRoom->isUserIn()) {
            abort(404);
        }

        $message = Message::create([
            'user_id' => auth()->user()->id,
            'chat_room_id' => $validated['chat_room_id'],
            'message' => $validated['message'],
        ]);

        event(new MessageEvent($message['id'], $validated['chat_room_id']));

        return response()->json([
            'data' => new MessageResource($message),
        ]);
    }

    public function getMessage(Message $message) {
        if ($message->chatRoom->isUserIn()) {
            return new MessageResource($message);
        }
        abort(404);
    }

    /**
     * @OA\Post(
     *      path="/chat_room_messages",
     *      operationId="post",
     *      tags={"Message"},
     *      summary="Atgriež ziņas",
     *      description="Atgriež visas ziņas no konkrētas grupas ar pagināciju - 10",
     *      security={{ "bearer": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  required = {"chat_room_id"},
     *                  @OA\Property(format="integer", description="Saraktes grupas id", property="chat_room_id"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/MessageResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated.",
     *      )
     *)
     */

    public function chatRoomMessages(ChatRoom $chatRoom) {
        if (!$chatRoom->isUserIn()) {
            abort(404);
        }
        $chatRoomMessages = Message::where('chat_room_id', $chatRoom->id)
            ->orderBy('created_at')
            ->get()
            ->toArray();
        $result = [];
        foreach ($chatRoomMessages as $key => $message) {
            $previousMessageDate = Carbon::parse($result[$key-1]['created_at'] ?? null)->format('Y-m-d');
            $currentMessageDate = Carbon::parse($message['created_at'])->format('Y-m-d');
            if (isset($previousMessageDate) && $previousMessageDate !== $currentMessageDate) {
                $result[] = [
                    'created_at' => $currentMessageDate,
                ];
            }
            $result[] = [
                'id' => $message['id'],
                'user' => $message['user_id'],
                'chat_room_id' => $message['chat_room_id'],
                'message' => $message['message'],
                'date' => Carbon::parse($message['created_at'])->format('Y-m-d'),
                'time' => Carbon::parse($message['created_at'])->format('H:m'),
            ];
        }
        return response()->json([
            'data' => $result,
        ]);
    }
}
