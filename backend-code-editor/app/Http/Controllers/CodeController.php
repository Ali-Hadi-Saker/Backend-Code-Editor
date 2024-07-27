<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;

class CodeController extends Controller
{
    public function readAllcodes(){
        $code = Code::all();
        return response()->json([
            'code' => $code
        ], 200);
    }

    public function readCode($id){
        $code = Code::find($id);
        return response()->json([
            'code' => $code
        ], 200);
    }

    public function deleteCode($id){
        $code = Code::find($id);
        if($code){
            $code->delete();
            return response()->json([
                'code' => 'deleted successfully'
            ], 200);
        } else {
            return response()->json(['message' => 'Code snippet not found'], 404);
        }
        }

    

    public function createCode(Request $req){
        $validated_data = $req->validate([
            'user_id' => 'required|numeric|exists:users,id',
            'file_name' => 'required|string',
            'code' => 'required|string'
        ]);
        $code = Code::create($validated_data);
        return response()->json([
            'status' => 'success',
            'code' => $code
        ], 201);
    }

    public function updateCode(Request $req, $id){
        $code = Code::find($id);
        if($code){
            $validated_data = $req->validate([
                'user_id' => 'required|numeric|exists:users,id',
                'file_name' => 'required|string|max:255',
                'code' => 'required|text'
            ]);
            $code->update($validated_data);
        }
        return response()->json(['code' => 'updated successfully'],204);
    }
}
