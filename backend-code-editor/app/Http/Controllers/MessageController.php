<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    // public function createMessage(Request $req){
    //     $validated_data = $req->validate([
    //         'sender_id' => 'required|numeric|exists:users,id',
    //         'receiver_id' => 'required|numeric|exists:users,id',
    //         'message' => 'required|string'
    //     ]);
    //     $message = Message::create($validated_data);
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => $message
    //     ], 201);
    // }



    public function updateMessage(Request $req, $id)
    {
        $message = Message::find($id);
        if (!$message) {
            return response()->json(['error' => 'Message not found'], 404);
        }
    
        $validated_data = $req->validate([
            'sender_id' => 'required|numeric|exists:users,id',
            'receiver_id' => 'required|numeric|exists:users,id',
            'message' => 'required|string'
        ]);
    
        $message->update($validated_data);
    
        return response()->json(['message' => 'Updated successfully'], 200);
    }




  public function getMessagesByReceiverId(Request $req, $receiver_id) {
    // Check if the user is authenticated
    if (!Auth::check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    // Validate the receiver_id parameter
    $validator = Validator::make(['receiver_id' => $receiver_id], [
        'receiver_id' => 'required|numeric|exists:users,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid receiver ID',
            'errors' => $validator->errors(),
        ], 400);
    }

    // Get the sender ID from the authenticated user
    $sender_id = Auth::id();

    // Fetch messages between the sender and receiver
    $messages = Message::where(function($query) use ($sender_id, $receiver_id) {
        $query->where('sender_id', $sender_id)
              ->where('receiver_id', $receiver_id);
    })->orWhere(function($query) use ($sender_id, $receiver_id) {
        $query->where('sender_id', $receiver_id)
              ->where('receiver_id', $sender_id);
    })->orderBy('created_at', 'asc')->get();

    // Return the messages
    return response()->json([
        'status' => 'success',
        'messages' => $messages->map(function ($message) {
            return [
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'message' => $message->message,
                'time' => $message->created_at->format('H:i'),
            ];
        }),
    ]);
}

public function createMessage(Request $req) {
    // Check if the user is authenticated
    if (!Auth::check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    // Validate the request parameters
    $validator = Validator::make($req->all(), [
        'receiver_id' => 'required|numeric|exists:users,id',
        'message' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid input',
            'errors' => $validator->errors(),
        ], 400);
    }

    // Get the sender ID from the authenticated user
    $sender_id = Auth::id();

    // Create the message
    $message = new Message();
    $message->sender_id = $sender_id;
    $message->receiver_id = $req->input('receiver_id');
    $message->message = $req->input('message');
    $message->save();

    // Return the created message
    return response()->json([
        'status' => 'success',
        'message' => [
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'message' => $message->message,
            'time' => $message->created_at->format('H:i'),
        ],
    ]);
}

}






