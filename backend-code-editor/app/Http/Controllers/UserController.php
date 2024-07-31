<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAllUsers(){
        $users = User::all();
        return response()->json([
            'user' => $users
        ], 200);
    }

    public function getAllUsersForAdmin(){
        $users = User::all();
        return response()->json([
            'user' => $users
        ], 200);
    }

    

    public function createUser(Request $req){

        $data = $req->input('users');
        $rules = [
            '*.name' => 'required|string',
            '*.email' => 'required|string',
            '*.password' => 'required|string'
        ];
    
        $validator = Validator::make($data, $rules);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        foreach ($data as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => bcrypt($userData['password']),
            ]);
        }
    
        return response()->json([
            'status' => 'success',
            'message' => 'Users created successfully'
        ], 201);
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

        public function updateUser(Request $req, $id){
            $user = User::find($id);
            if(!$user){
                return response()->json(['message' => 'User not found'], 404);
            }
    
            $data = $req->only(['name', 'email', 'password']);
            $rules = [
                'name' => 'sometimes|string',
                'email' => 'sometimes|string|unique:users,email,'.$id,
                'password' => 'sometimes|string'
            ];
    
            $validator = Validator::make($data, $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }
    
            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }
    
            $user->update($data);
    
            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'user' => $user
            ], 200);
        }

}
