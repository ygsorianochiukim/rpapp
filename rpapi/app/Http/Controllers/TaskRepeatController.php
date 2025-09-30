<?php

namespace App\Http\Controllers;

use App\Models\TaskRepeat;
use Illuminate\Http\Request;

class TaskRepeatController extends Controller
{
    public function displayRepeat(){
        $displayDueRepeat = TaskRepeat::where("is_active", "1");

        return response()->json($displayDueRepeat);
    }

    public function newDueDateRepeat(Request $request){
        $dueDateRepeat = $request->validate([
            "task_i_information_id" => "required",
            "repeat_frequency" => "string|required",
            "created_by" => "required",
        ]);
        $dueDateRepeat['is_Active'] = '1';
        $dueDateRepeat['created_date'] = date('Y-m-d H:i:s');
        $DueFieldRepeat = TaskRepeat::create($dueDateRepeat);
        return response()->json(['Submit Due Date Repeat' , $DueFieldRepeat]);
    }

    public function display_task_user($id){
        $step = TaskRepeat::where('task_i_information_id', '=', $id)->get();
        return response()->json($step);
    }

    public function updateSelectedRepeatFrequency($id, Request $request)
    {
        $request->validate([
            'repeat_frequency' => 'required|string',
        ]);

        $repeatUpdate = TaskRepeat::where('task_repeat_id', $id)->first();
        if (!$repeatUpdate) {
            return response()->json(['message' => 'Task repeat frequency not found'], 404);
        }
        
        $repeatUpdate->repeat_frequency = $request->repeat_frequency;
        $repeatUpdate->save();
        return response()->json([
            'message' => 'Task repeat frequency updated successfully',
            'repeat_frequency' => $repeatUpdate->repeat_frequency
        ]);
    }
}
