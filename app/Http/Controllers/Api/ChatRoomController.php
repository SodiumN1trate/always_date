<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRoomRequest;
use App\Http\Resources\ChatRoomResource;
use App\Http\Resources\UserResource;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ChatRoomController extends Controller {
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
    public function store(Request $request) {
        $validated = $request->validate([
            'user2_id' => 'required',
        ]);
        $validated['user1_id'] = auth()->user()->id;

        if ($validated['user1_id'] == $validated['user2_id']) {
            return response()->json([
                'error' => [
                    'data' => 'Nevar izveidot saraksti pats ar sevi',
                ]
            ], 400);
        }

        $chatRoom = ChatRoom::where('user1_id', auth()->user()->id)
            ->orWhere('user2_id', auth()->user()->id)
            ->firstOrFail();
        if($chatRoom) {
            abort(404);
        }
        return new ChatRoomResource(ChatRoom::create($validated));
    }

    public function userChats() {
        $usersChat = ChatRoom::where('user1_id', auth()->user()->id)
            ->orWhere('user2_id', auth()->user()->id)
            ->get()
            ->map(function ($chatRoom) {
                if($chatRoom->user1_id === auth()->user()->id) {
                    $user = $chatRoom->user2_id;
                } else {
                    $user = $chatRoom->user1_id;
                }

                $user = User::find($user);
                if (isset(parse_url($user->avatar)['host']) == 'graph.facebook.com') {
                    $avatar = $user->avatar;
                } else {
                    $avatar = URL::signedRoute('user.image', ['user' => $user['id']]);
                }

                return [
                    'chat_room_id' => $chatRoom['id'],
                    'id' => $user['id'],
                    'avatar' => $avatar,
                    'firstname' => $user['firstname'],
                    'lastname' => $user['lastname'],
                ];
            });
        return response()->json([
            'data' => $usersChat,
        ]);
    }
}
