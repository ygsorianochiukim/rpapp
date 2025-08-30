<?php

namespace App\Http\Controllers;

use App\Models\TaskFile;
use Illuminate\Http\Request;

class TaskFileController extends Controller
{
    public function displayTaskFile(){
        $taskFile = TaskFile::all();

        return response()->json($taskFile);
    }

    public function addTaskFile(Request $request)
    {
        $taskFile = $request->validate([
            "task_i_information_id" => "required",
            "task_file_name" => "string|required",
            "file" => "string|required",
            "created_by" => "required",
        ]);

        $taskFile['is_Active'] = '1';
        $taskFile['created_date'] = now();
        if (str_contains($taskFile['file'], 'base64,')) {
            $taskFile['file'] = explode(',', $taskFile['file'])[1];
        }
        $taskFileRecord = TaskFile::create($taskFile);
        return response()->json([
            'message' => 'Submit Task File',
            'data' => $taskFileRecord
        ], 201);
    }
    
    public function display_task_user($id){
        $step = TaskFile::where('task_i_information_id', '=', $id)->get();
        return response()->json($step);
    }

    public function downloadTaskFile($id)
    {
        $taskFile = TaskFile::findOrFail($id);

        $base64 = $taskFile->file;

        // Decode base64
        $decoded = base64_decode($base64);

        // Detect mime type from decoded data
        $finfo = finfo_open();
        $mimeType = finfo_buffer($finfo, $decoded, FILEINFO_MIME_TYPE);
        finfo_close($finfo);

        // Build data URL
        $fileData = "data:$mimeType;base64," . $base64;

        return response()->json([
            'file_name' => $taskFile->task_file_name,
            'file_data' => $fileData
        ]);
    }
}
