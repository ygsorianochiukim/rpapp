<?php

namespace App\Http\Controllers;

use App\Models\TaskLogs;
use Illuminate\Http\Request;

class TaskLogsController extends Controller
{
    public function displayLogs(){
        $taskLogs = TaskLogs::all();

        return response()->json($taskLogs);
    }

    public function addTaskLogs(Request $request){
        $TaskLogs = $request->validate([
            "s_bpartner_employee_id" => "integer|required",
            "task_i_information_id" => "integer|required",
            "created_by" => "integer|required",
        ]);
        $TaskLogs['is_active'] = '1';
        $TaskLogs['TaskDateComplete'] = date('Y-m-d H:i:s');
        $TaskLogs['created_date'] = date('Y-m-d H:i:s');

        $TaskLogsData = TaskLogs::create($TaskLogs);
        return response()->json(['Logs Submitted' , $TaskLogsData]);
    }
    public function displayComplete(){
        $CompletedTask = TaskLogs::with(['user.complete' , 'task.complete'])
        ->get();

        return response()->json($CompletedTask);
    }

    public function displayCompleteTaskPerUser($id)
    {
        $CompletedTask = TaskLogs::with(['user.complete', 'task.complete'])
            ->where('s_bpartner_employee_id', $id)
            ->get();

        return response()->json($CompletedTask);
    }
}
