<?php

namespace App\Http\Controllers;

use App\Models\TaskDueDate;
use App\Models\TaskRepeat;
use DateTime;
use Illuminate\Http\Request;

class TaskDueController extends Controller
{
    public function displayTaskDue(){
        $displayDue = TaskDueDate::all();

        return response()->json($displayDue);
    }

    public function newDueDate(Request $request){
        $dueDate = $request->validate([
            "task_i_information_id" => "required",
            "date_selected" => "string|required",
            "created_by" => "required",
        ]);

        $DateSelected = $request->date_selected;
        if($DateSelected == "Today"){
            $dueDate['due_date'] = date('Y-m-d H:i:s');
        }
        else if($DateSelected == "Tomorrow"){
            $dueDate['due_date'] = date('Y-m-d H:i:s', strtotime('+1 day'));
        }
        else if($DateSelected == "Next Week"){
            $dueDate['due_date'] = date('Y-m-d H:i:s', strtotime('+7 day'));
        }
        else{
            $dueDate = $request->validate([
                "due_date" => "date|required",
                "task_i_information_id" => "required",
                "date_selected" => "string|required",
                "created_by" => "required",
            ]);
        }
        $dueDate['is_Active'] = '1';
        $dueDate['created_date'] = date('Y-m-d H:i:s');
        $DueField = TaskDueDate::create($dueDate);
        return response()->json(['Submit Due Date' , $DueField]);
    }
    public function display_task_user($id){
        $taskDue = TaskDueDate::where('task_i_information_id', '=', $id)->get();
        return response()->json($taskDue);
    }

    public function updatetaskDue($id, Request $request)
    {
        $dueUpdate = TaskDueDate::where('task_i_information_id', '=', $id)->first();
        $DueFrequency = TaskRepeat::where('task_i_information_id', '=', $id)->first();

        if (!$dueUpdate || !$DueFrequency) {
            return response()->json(['message' => 'Task or frequency not found'], 404);
        }

        $currentDueDate = $dueUpdate->due_date 
            ? new DateTime($dueUpdate->due_date) 
            : new DateTime();

        $DateFrequency = $DueFrequency->repeat_frequency;

        switch ($DateFrequency) {
            case "Daily":
                $currentDueDate->modify('+1 day');
                break;

            case "Weekdays":
                $currentDueDate->modify('next monday');
                break;

            case "Monthly":
                $currentDueDate->modify('+1 month');
                break;

            case "Yearly":
                $currentDueDate->modify('+1 year');
                break;
        }

        $dueUpdate->due_date = $currentDueDate->format('Y-m-d H:i:s');
        $dueUpdate->save();

        return response()->json(['message' => 'Due date updated successfully']);
    }
}
