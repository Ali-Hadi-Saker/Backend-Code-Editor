<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAllUsers(){
        $users = User::all();
        return response()->json([
            'user' => $users
        ], 200);
    }

    public function deleteUser($id){
        $user = User::find($id);
        if($user){
            $user->delete();
            return response()->json([
                'user' => 'deleted successfully'
            ], 200);
        } else {
            return response()->json(['message' => 'user not found'], 404);
        }
        }

}
