<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

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



    public function compileCode(Request $req, ){

        $code = $req->input('code');

        //  Python code needs to be in a file to be executed
        $filePath = storage_path('app/code_snippets/temp_script.py');
        file_put_contents($filePath, $code);

        // The Process component allows you to run external commands
        $pythonPath = 'C:\Users\moussa.haidar\AppData\Local\Programs\Python\Python312\python.exe';
        $process = new Process([$pythonPath, $filePath]);
        $process->run();

        // getErrorOutput() provides details about what went wrong
        if ($process->isSuccessful()) {
            return response()->json([
                'status' => 'success',
                'output' => $process->getOutput()
            ], 200);
        } else {
            // Extract the error message excluding the file path
            $errorOutput = $process->getErrorOutput();
            $filteredError = preg_replace('/.*File ".*", line \d+, in <module>\r\n/', '', $errorOutput);
        
            return response()->json([
                'status' => 'error',
                'message' => 'Error during code execution',
                'details' => $filteredError
            ], 200);
        }
        

    }
}
