<?php

namespace App\Http\Controllers;

use App\Models\RemindTask;
use App\Models\StepTask;
use App\Models\Task;
use App\Models\TaskBank;
use App\Models\TaskDueDate;
use App\Models\TaskFile;
use App\Models\TaskNotes;
use App\Models\TaskRepeat;
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
            'task_notes'  => 'nullable|string',
            'task_step'  => 'nullable|string',
            'file'  => 'nullable|string',
            'repeat_frequency'  => 'nullable|string',
            'remind_task'  => 'nullable|string',
        ]);

        $RemindMeValue = $request->remind_task;
        if($RemindMeValue == 'Tomorrow'){
            $validated['date_remind'] = date('Y-m-d H:i:s', strtotime('+1 day'));
        }
        else if($RemindMeValue == 'Next Week'){
            $validated['date_remind'] = date('Y-m-d H:i:s', strtotime('+7 day'));
        }
        else if($RemindMeValue == 'Later Today'){
            $validated['date_remind'] = date('Y-m-d H:i:s');
        }

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
        }

        $validated['is_active']    = 1;
        $validated['created_date'] = now();
        if (str_contains($validated['file'], 'base64,')) {
            $taskFile['file'] = explode(',', $validated['file'])[1];
        }
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
            'user_id'     => 'required|integer',
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
                    $existingTask = Task::where('s_bpartner_employee_id', $userId)
                        ->where('task_bank_id', $bank->task_bank_id)
                        ->first();

                    if (!$existingTask) {
                        $task = Task::create([
                            'task_name'              => $bank->task_name,
                            'description'            => $bank->description,
                            'task_category'          => $bank->task_category ?? 'Task',
                            's_bpartner_employee_id' => $userId,
                            'position_id'            => $positionId,
                            'task_status'            => 'pending',
                            'is_active'              => 1,
                            'created_by'             => $createdBy,
                            'created_date'           => now(),
                            'task_bank_id'           => $bank->task_bank_id,
                        ]);
                        if ($bank->due_date) {
                            TaskDueDate::create([
                                'task_i_information_id' => $task->task_i_information_id,
                                'due_date'              => $bank->due_date,
                                'is_Active'             => 1,
                                'created_by'            => $createdBy,
                                'created_date'          => now(),
                            ]);
                        }
                        if ($bank->repeat_frequency) {
                            TaskRepeat::create([
                                'task_i_information_id' => $task->task_i_information_id,
                                'repeat_frequency'      => $bank->repeat_frequency,
                                'is_Active'             => 1,
                                'created_by'            => $createdBy,
                                'created_date'          => now(),
                            ]);
                        }
                        $fileCounter = 1;
                        if ($bank->file) {
                            TaskFile::create([
                                'task_i_information_id' => $task->task_i_information_id,
                                'task_file_name'        => $bank->task_file_name ?? 'File-' . $fileCounter,
                                'file'                  => $bank->file,
                                'is_Active'             => 1,
                                'created_by'            => $createdBy,
                                'created_date'          => now(),
                            ]);
                            $fileCounter++;
                        }

                        if ($bank->task_notes) {
                            TaskNotes::create([
                                'task_i_information_id' => $task->task_i_information_id,
                                'task_note'             => $bank->task_notes,
                                'is_active'             => 1,
                                'created_by'            => $createdBy,
                                'created_date'          => now(),
                            ]);
                        }
                        if ($bank->task_step) {
                            StepTask::create([
                                'task_i_information_id'  => $task->task_i_information_id,
                                'task_steps_description' => $bank->task_step,
                                'is_active'              => 1,
                                'created_by'             => $createdBy,
                                'created_date'           => now(),
                            ]);
                        }
                        if ($bank->remind_task) {
                            RemindTask::create([
                                'task_i_information_id' => $task->task_i_information_id,
                                'remind_task'           => $bank->remind_task,
                                'date_remind'           => $bank->date_remind,
                                'is_Active'             => 1,
                                'created_by'            => $createdBy,
                                'created_date'          => now(),
                            ]);
                        }
                        $createdTasks[] = $task;
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Tasks synced successfully with related data',
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

    public function updateAndSync(Request $request, $id)
    {
        $taskBank = TaskBank::findOrFail($id);
        $taskBank->update($request->only([
            'task_name',
            'description',
            'task_category',
            'position_id',
            'task_notes',
            'task_step',
            'repeat_frequency',
            'remind_task',
            'file'
        ]));
        $tasks = Task::where('task_bank_id', $taskBank->task_bank_id)
            ->where('is_active', 1)
            ->get();

        foreach ($tasks as $task) {
            $task->update([
                'task_name'     => $taskBank->task_name,
                'description'   => $taskBank->description,
                'task_category' => $taskBank->task_category,
                'position_id'   => $taskBank->position_id,
            ]);
            if ($taskBank->repeat_frequency) {
                TaskRepeat::updateOrCreate(
                    ['task_i_information_id' => $task->task_i_information_id],
                    [
                        'repeat_frequency' => $taskBank->repeat_frequency,
                        'is_active'        => 1,
                        'created_by'       => $request->created_by ?? Auth::id(),
                        'created_date'     => now(),
                    ]
                );
            }
            if ($taskBank->task_notes) {
                TaskNotes::updateOrCreate(
                    ['task_i_information_id' => $task->task_i_information_id],
                    [
                        'task_note'     => $taskBank->task_notes,
                        'is_active'     => 1,
                        'created_by'    => $request->created_by ?? Auth::id(),
                        'created_date'  => now(),
                    ]
                );
            }
            if ($taskBank->task_step) {
                StepTask::updateOrCreate(
                    ['task_i_information_id' => $task->task_i_information_id],
                    [
                        'task_steps_description' => $taskBank->task_step,
                        'is_active'              => 1,
                        'created_by'             => $request->created_by ?? Auth::id(),
                        'created_date'           => now(),
                    ]
                );
            }
            if ($taskBank->remind_task) {
                RemindTask::updateOrCreate(
                    ['task_i_information_id' => $task->task_i_information_id],
                    [
                        'remind_task'  => $taskBank->remind_task,
                        'date_remind'  => $taskBank->date_remind,
                        'is_active'    => 1,
                        'created_by'   => $request->created_by ?? Auth::id(),
                        'created_date' => now(),
                    ]
                );
            }
            if ($taskBank->file) {
                TaskFile::updateOrCreate(
                    ['task_i_information_id' => $task->task_i_information_id],
                    [
                        'task_file_name' => $taskBank->task_file_name ?? 'File-1',
                        'file'           => $taskBank->file,
                        'is_active'      => 1,
                        'created_by'     => $request->created_by ?? Auth::id(),
                        'created_date'   => now(),
                    ]
                );
            }
        }
        return response()->json([
            'message' => 'TaskBank updated and synced successfully',
            'data'    => $taskBank
        ]);
    }
    public function syncSingleTask(Request $request, $taskBankId)
    {
        $request->validate([
            'position_id' => 'required|integer',
            'user_id'     => 'required|integer',
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

            $bank = TaskBank::findOrFail($taskBankId);
            $createdTasks = [];

            foreach ($users as $userId) {
                $existingTask = Task::where('s_bpartner_employee_id', $userId)
                    ->where('task_bank_id', $bank->task_bank_id)
                    ->first();

                if (!$existingTask) {
                    $task = Task::create([
                        'task_name'              => $bank->task_name,
                        'description'            => $bank->description,
                        'task_category'          => $bank->task_category ?? 'Task',
                        's_bpartner_employee_id' => $userId,
                        'position_id'            => $positionId,
                        'task_status'            => 'pending',
                        'is_active'              => 1,
                        'created_by'             => $createdBy,
                        'created_date'           => now(),
                        'task_bank_id'           => $bank->task_bank_id,
                    ]);

                    if ($bank->due_date) {
                        TaskDueDate::create([
                            'task_i_information_id' => $task->task_i_information_id,
                            'due_date'              => $bank->due_date,
                            'is_Active'             => 1,
                            'created_by'            => $createdBy,
                            'created_date'          => now(),
                        ]);
                    }

                    if ($bank->repeat_frequency) {
                        TaskRepeat::create([
                            'task_i_information_id' => $task->task_i_information_id,
                            'repeat_frequency'      => $bank->repeat_frequency,
                            'is_Active'             => 1,
                            'created_by'            => $createdBy,
                            'created_date'          => now(),
                        ]);
                    }

                    if ($bank->file) {
                        TaskFile::create([
                            'task_i_information_id' => $task->task_i_information_id,
                            'task_file_name'        => $bank->task_file_name ?? 'File-1',
                            'file'                  => $bank->file,
                            'is_Active'             => 1,
                            'created_by'            => $createdBy,
                            'created_date'          => now(),
                        ]);
                    }

                    if ($bank->task_notes) {
                        TaskNotes::create([
                            'task_i_information_id' => $task->task_i_information_id,
                            'task_note'             => $bank->task_notes,
                            'is_active'             => 1,
                            'created_by'            => $createdBy,
                            'created_date'          => now(),
                        ]);
                    }

                    if ($bank->task_step) {
                        StepTask::create([
                            'task_i_information_id'  => $task->task_i_information_id,
                            'task_steps_description' => $bank->task_step,
                            'is_active'              => 1,
                            'created_by'             => $createdBy,
                            'created_date'           => now(),
                        ]);
                    }

                    if ($bank->remind_task) {
                        RemindTask::create([
                            'task_i_information_id' => $task->task_i_information_id,
                            'remind_task'           => $bank->remind_task,
                            'date_remind'           => $bank->date_remind,
                            'is_Active'             => 1,
                            'created_by'            => $createdBy,
                            'created_date'          => now(),
                        ]);
                    }

                    $createdTasks[] = $task;
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Single task synced successfully',
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
