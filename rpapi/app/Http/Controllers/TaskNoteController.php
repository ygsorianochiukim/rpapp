<?php

namespace App\Http\Controllers;

use App\Models\TaskNotes;
use Illuminate\Http\Request;

class TaskNoteController extends Controller
{
    public function displayNote(){
        $displayNote = TaskNotes::all();
        return response()->json($displayNote);
    }

    public function addNewNotes(Request $request){
        $validateNotes = $request->validate([
            "task_i_information_id" => "required",
            "task_note" => "string|required",
            "is_Active" => "boolean|required",
            "created_by" => "required",
        ]);
        $validateNotes['created_date'] = date('Y-m-d H:i:s');
        $taskNotes = TaskNotes::create($validateNotes);
        return response()->json(['Noted Submitted',$taskNotes]);
    }
    public function display_task_user($id){
        $step = TaskNotes::where('task_i_information_id', '=', $id)->get();
        return response()->json($step);
    }
}
