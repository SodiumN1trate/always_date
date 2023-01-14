<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageEvent;
use App\Http\Resources\MessageResource;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            'chat_room_id' => 'required',
            'message' => 'required',
        ]);

        $chatRoom = ChatRoom::where('id', $validated['chat_room_id'])->first();

        if (!$chatRoom) {
            return response()->json([
                'error' => [
                    'data' => 'Nevar nosūtīt ziņu svešā chat room.',
                ]
            ], 400);
        } else {
            // TODO
            broadcast(new MessageEvent(auth()->user()->id, $validated['chat_room_id'], $validated['message']));

            $savedMessage = Message::create([
                'user_id' => auth()->user()->id,
                'chat_room_id' => $validated['chat_room_id'],
                'message' => $validated['message'],
            ]);

            return response()->json([
                'Success' => 'Ziņa tika nosūtīta.',
                'message' => new MessageResource($savedMessage),
            ]);
        }
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
    // TODO
    public function chatRoomMessages(ChatRoom $chatRoom) {
        $chatRoomMessages = Message::where('chat_room_id', $chatRoom->id)->get();

        return MessageResource::collection($chatRoomMessages);
    }

}
