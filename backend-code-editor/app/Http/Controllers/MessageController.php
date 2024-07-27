<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function readAllMessages(){
        $message = Message::all();
        return response()->json([
            'message' => $message
        ], 200);
    }

    public function readMessage($id){
        $message = Message::find($id);
        return response()->json([
            'message' => $message
        ], 200);
    }

    public function deleteMessage($id){
        $message = Message::find($id);
        $message->delete();
        return response()->json([
            'message' => 'deleted successfully'
        ], 204);
    }

    public function createMessage(Request $req){
        $validated_data = $req->validate([
            'sender_id' => 'required|numeric|exists:users,id',
            'sender_id' => 'required|numeric|existts:user,id',
            'message' => 'required|string'
        ]);
        $message = Message::created($validated_data);
        return response()->json([
            'message' => $message
        ], 201);
    }

    public function updateMessage(Request $req, $id){
        $message = Message::find($id);
        if($message){
            $validated_data = $req->validate([
                'sender_id' => 'required|numeric|exists:users,id',
                'sender_id' => 'required|numeric|existts:user,id',
                'message' => 'required|string'
            ]);
            $message->update($validated_data);
        }
        return response()->json(['message' => 'updated successfully']);
    }
}
