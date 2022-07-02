<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRoomRequest;
use App\Http\Resources\ChatRoomResource;
use App\Models\ChatRoom;
use Illuminate\Http\Request;

class ChatRoomController extends Controller
{
    /**
     * @OA\Get(
     *      path="/chat_room",
     *      operationId="getChatRoom",
     *      tags={"Chat room"},
     *      summary="Atgriež sarakstes grupas",
     *      description="Atgriež visas sarakstes grupas",
     *      security={{ "bearer": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ChatRoomResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      )
     *)
     */
    public function index()
    {
        return ChatRoomResource::collection(ChatRoom::all());
    }

    /**
     * @OA\Post(
     *      path="/chat_room",
     *      operationId="postChatRoom",
     *      tags={"Chat room"},
     *      summary="Izvedo sarakstes grupu",
     *      description="Izveido sarakstes grupu starp 2 cilvēkiem, autorizēto lietotāju un otru izvēlēto lietotāju",
     *      security={{ "bearer": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  required = {"user2_id"},
     *                  @OA\Property(format="integer", description="Otra lietotāja id ar kuru tiks izveidota sarakstes grupa", property="user2_id"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ChatRoomResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Nevar izveidot saraksti ja user1_id un user2_id ir vienādi.",
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="Chat room jau pastāv starp šiem diviem lietotājiem.",
     *      )
     *)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user2_id' => 'required'
        ]);
        $validated['user1_id'] = auth()->user()->id;

        $chatRoom = ChatRoom::where('user1_id', auth()->user()->id)->where('user2_id', $validated['user2_id'])
            ->orWhere('user2_id', auth()->user()->id)->where('user1_id', $validated['user2_id'])->first();

        if ($validated['user1_id'] == $validated['user2_id']) {
            return response()->json([
                'error' => [
                    'data' => 'Nevar izveidot saraksti ja user1_id un user2_id ir vienādi.',
                ]
            ], 400);
        } elseif ($chatRoom) {
            return response()->json([
                'error' => [
                    'data' => 'Chat room jau pastāv starp šiem diviem lietotājiem.',
                ]
            ]);
        }

        return new ChatRoomResource(ChatRoom::create($validated));
    }

    /**
     * @OA\Get(
     *      path="/chat_room/{id}",
     *      operationId="getChatRoomById",
     *      tags={"Chat room"},
     *      summary="Atgriež saraksti",
     *      description="Atgriež konkrētu saraksti pēc id",
     *      security={{ "bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Chat room id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ChatRoomResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated.",
     *      ),
     * )
     */
    public function show(ChatRoom $chatRoom)
    {
        return new ChatRoomResource($chatRoom);
    }

    /**
     * @OA\Put(
     *      path="/chat_room/{id}",
     *      operationId="updateChatRoom",
     *      tags={"Chat room"},
     *      summary="Rediģē sarakstes grupu",
     *      description="Rediģē konkrētu sarakstes grupu pēc id",
     *      security={{ "bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Chat room id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(format="integer", description="Pirmā lietotāja id", property="user1_id"),
     *                  @OA\Property(format="integer", description="Otrā lietotāja id", property="user2_id"),
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ChatRoomResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      ),
     *)
     */
    public function update(Request $request, ChatRoom $chatRoom)
    {
        $chatRoom->update($request->validate([
            'user1_id' => '',
            'user2_id' => '',
        ]));

        return new ChatRoomResource($chatRoom);
    }

    /**
     * @OA\Delete(
     *      path="/chat_room/{id}",
     *      operationId="deleteChatRoom",
     *      tags={"Chat room"},
     *      summary="Izdzēš sarakstes grupu",
     *      description="Izdzēs konkrētu sarakstes grupu pēc id",
     *      security={{ "bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Chat room id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ChatRoomResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      ),
     * )
     */
    public function destroy(ChatRoom $chatRoom)
    {
        $chatRoom->delete();
        return new ChatRoomResource($chatRoom);
    }
}
