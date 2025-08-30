<?php

namespace App\Http\Controllers;

use App\Models\StepTask;
use Illuminate\Http\Request;

class StepTaskController extends Controller
{
    public function displayStepTask(){
        $displayTask = StepTask::all();
        return response()->json($displayTask);
    }
    public function newStepTask(Request $request){
        $validateSteps = $request->validate([
            "task_i_information_id" => "required",
            "task_steps_description" => "string|required",
            "created_by" => "required",
        ]);
        $validateSteps['is_active'] = '1';
        $validateSteps['created_date'] = date('Y-m-d H:i:s');

        return StepTask::create($validateSteps);
    }

    public function display_task_user($id){
        $step = StepTask::where('task_i_information_id', '=', $id)->get();
        return response()->json($step);
    }

}
