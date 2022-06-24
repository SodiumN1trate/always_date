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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ChatRoomResource::collection(ChatRoom::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
                    'data' => 'Nevar izveidot saraksti ja user1_id un user2_id ir vienādi',
                ]
            ], 400);
        } elseif ($chatRoom) {
            return response()->json([
                'error' => [
                    'data' => 'Chat room jau pastāv starp šiem diviem lietotājiem',
                ]
            ]);
        }

        return new ChatRoomResource(ChatRoom::create($validated));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ChatRoom $chatRoom)
    {
        return new ChatRoomResource($chatRoom);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChatRoom $chatRoom)
    {
        $chatRoom->delete();
        return new ChatRoomResource($chatRoom);
    }
}
