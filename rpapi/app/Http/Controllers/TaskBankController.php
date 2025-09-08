<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskBank;
use App\Models\TaskDueDate;
use App\Models\UserAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskBankController extends Controller
{
    public function index()
    {
        $tasks = TaskBank::where('is_active', 1)
        ->with('position')
        ->get();

        return response()->json($tasks);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_name'      => 'required|string',
            'description'    => 'required|string',
            'task_category'  => 'required|string',
            'position_id'    => 'required|integer',
            'created_by'     => 'required|integer',
            'date_selected'  => 'nullable|string',
            'due_date'       => 'nullable|date'
        ]);
        if ($validated['date_selected'] === "Today") {
            $validated['due_date'] = now();
        } 
        else if ($validated['date_selected'] === "Tomorrow") {
            $validated['due_date'] = now()->addDay();
        } 
        else if ($validated['date_selected'] === "Next Week") {
            $validated['due_date'] = now()->addWeek();
        } 
        else if ($validated['date_selected'] === "Pick a Date") {
            $request->validate([
                'due_date' => 'required|date'
            ]);
            // due_date already set from request
        }

        $validated['is_active']    = 1;
        $validated['created_date'] = now();

        $task = TaskBank::create($validated);

        return response()->json([
            'message' => 'Task template created successfully',
            'data'    => $task
        ]);
    }

    public function update(Request $request, $id)
    {
        $task = TaskBank::findOrFail($id);

        $task->update($request->only([
            'task_name',
            'description',
            'task_category',
            'position_id',
            'due_date'
        ]));

        return response()->json([
            'message' => 'Task template updated successfully',
            'data'    => $task
        ]);
    }
    public function destroy($id)
    {
        $task = TaskBank::findOrFail($id);

        $task->update(['is_active' => 0]);

        return response()->json([
            'message' => 'Task template removed (soft delete)',
            'data'    => $task
        ]);
    }
    public function syncByPosition(Request $request)
    {
        $request->validate([
            'position_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $positionId = $request->position_id;
        $createdBy  = $request->user_id;

        DB::beginTransaction();
        try {
            $users = UserAccess::where('position_id', $positionId)
                ->where('is_active', 1)
                ->pluck('s_bpartner_employee_id');

            if ($users->isEmpty()) {
                return response()->json(['message' => 'No users found under this position'], 404);
            }

            $taskBanks = TaskBank::where('position_id', $positionId)->get();
            $createdTasks = [];

            foreach ($users as $userId) {
                foreach ($taskBanks as $bank) {
                    // ✅ Check if task already exists for this user + bank
                    $existingTask = Task::where('s_bpartner_employee_id', $userId)
                        ->where('task_bank_id', $bank->task_bank_id)
                        ->first();

                    if (!$existingTask) {
                        $task = Task::create([
                            'task_name' => $bank->task_name,
                            'description' => $bank->description,
                            'task_category' => $bank->task_category ?? 'Task',
                            's_bpartner_employee_id' => $userId,
                            'position_id' => $positionId,
                            'task_status' => 'pending',
                            'is_active' => 1,
                            'created_by' => $createdBy,
                            'created_date' => now(),
                            'task_bank_id' => $bank->task_bank_id, // ✅ prevent duplication
                        ]);

                        if ($bank->due_date) {
                            TaskDueDate::create([
                                'task_i_information_id' => $task->task_i_information_id,
                                'due_date' => $bank->due_date,
                                'is_Active' => 1,
                                'created_by' => $createdBy,
                                'created_date' => now(),
                            ]);
                        }

                        $createdTasks[] = $task;
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Tasks synced successfully',
                'tasks'   => $createdTasks
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sync failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
