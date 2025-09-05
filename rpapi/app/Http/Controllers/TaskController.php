<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskDueDate;
use App\Models\TeamAccess;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    public function displayTask()
    {
        $displayList = Task::where('task_status' , '=' , 'PENDING')->get();

        return response()->json($displayList);
    }

    public function displayUsersTask(Request $request, $id)
    {
        $dueDates = TaskDueDate::with(['Task.user'])
            ->whereHas('Task', function ($q) use ($id, $request) {
                $q->where('s_bpartner_employee_id', $id)
                ->filter($request->only(['type', 'status']));
            })
            ->where('is_active','=', '1')
            ->get();

        $data = $dueDates->map(function ($due) {
            return [
                'task_i_information_id' => $due->Task?->task_i_information_id,
                'task_name'             => $due->Task?->task_name,
                'description'           => $due->Task?->description,
                'due_date'              => $due->due_date,
                'task_status'           => $due->Task?->task_status,
                'firstname'             => $due->Task->user->firstname,
                'lastname'              => $due->Task->user->lastname,
                'email'                 => $due->Task->user->email,
            ];
        });

        return response()->json($data);
    }

    public function TaskDueDates(Request $request)
    {
        $dueDates = TaskDueDate::with(['task.user'])
            ->whereHas('task', function ($q) use ($request) {
                $q->filter($request->only(['assigned', 'type', 'status']))
                ->where('task_status', '!=', 'COMPLETE')
                ->where('is_active','=', '1');
            })
            ->get();

        $data = $dueDates->map(function ($due) {
            return [
                'task_i_information_id' => $due->Task?->task_i_information_id,
                'task_name'             => $due->Task?->task_name,
                'description'           => $due->Task?->description,
                'due_date'              => $due->due_date,
                'task_status'           => $due->Task?->task_status,
                'firstname'             => $due->Task->user->firstname,
                'lastname'              => $due->Task->user->lastname,
                'email'                 => $due->Task->user->email,
            ];
        });

        return response()->json($data);
    }

    // public function addTask(Request $request)
    // {
    //     $validateTask = $request->validate([
    //         'task_name' => 'string|required',
    //         'description' => 'string|required',
    //         'task_category' => 'string|required',
    //         's_bpartner_employee_id' => 'integer',
    //         'created_by' => 'integer|required',
    //         'task_status' => 'required',
    //         'position_id' => 'integer',
    //     ]);
    //     $validateTask['is_active'] = '1';
    //     $validateTask['created_date'] = date('Y-m-d H:i:s');
    //     return Task::create($validateTask);
    // }

    public function addTask(Request $request)
    {
        $validateTask = $request->validate([
            'task_name' => 'string|required',
            'description' => 'string|required',
            'task_category' => 'string|required',
            's_bpartner_employee_id' => 'integer|nullable',
            'created_by' => 'integer|required',
            'task_status' => 'required',
            'position_id' => 'integer|nullable',
        ]);

        $validateTask['is_active'] = 1;
        $validateTask['created_date'] = now();

        if ($validateTask['task_category'] === 'Task') {
            // Get all employees with this position_id
            $userAccessList = \App\Models\UserAccess::where('position_id', $validateTask['position_id'])
                ->where('is_active', 1)
                ->pluck('s_bpartner_employee_id');

            $tasks = [];
            foreach ($userAccessList as $employeeId) {
                $taskData = $validateTask;
                $taskData['s_bpartner_employee_id'] = $employeeId;
                $tasks[] = Task::create($taskData);
            }

            return response()->json([
                'message' => 'Tasks created for all users with this position',
                'data' => $tasks
            ]);
        }

        // Default: To-Do List → save as is
        $task = Task::create($validateTask);

        return response()->json([
            'message' => 'Task created',
            'data' => $task
        ]);
    }

    public function syncTasks(Request $request)
    {
        $request->validate([
            's_bpartner_employee_id' => 'required|integer'
        ]);

        $employeeId = $request->s_bpartner_employee_id;

        // 1. Get the employee's position
        $userAccess = \App\Models\UserAccess::where('s_bpartner_employee_id', $employeeId)
            ->where('is_active', 1)
            ->first();

        if (!$userAccess) {
            return response()->json([
                'message' => 'No active position found for this employee'
            ], 404);
        }

        $positionId = $userAccess->position_id;

        // 2. Get all base tasks (assigned to position but not to any specific employee yet)
        $baseTasks = \App\Models\Task::where('position_id', $positionId)
            ->whereNull('s_bpartner_employee_id') // base task (template)
            ->get();

        if ($baseTasks->isEmpty()) {
            return response()->json([
                'message' => 'No base tasks found for this position'
            ]);
        }

        $syncedTasks = [];

        foreach ($baseTasks as $baseTask) {
            // 3. Check if employee already has a copy of this task
            $exists = \App\Models\Task::where('position_id', $positionId)
                ->where('s_bpartner_employee_id', $employeeId)
                ->where('task_name', $baseTask->task_name) // match by task name
                ->exists();

            if (!$exists) {
                // 4. Duplicate base task for this employee
                $taskData = $baseTask->replicate()->toArray();
                unset($taskData['id']); // remove primary key
                $taskData['s_bpartner_employee_id'] = $employeeId;
                $taskData['created_date'] = now();

                $newTask = \App\Models\Task::create($taskData);
                $syncedTasks[] = $newTask;
            }
        }

        return response()->json([
            'message' => 'Tasks synced successfully',
            'added_count' => count($syncedTasks),
            'data' => $syncedTasks
        ]);
    }



    public function markasDone($id)
    {
        $taskInfo = Task::findorFail($id);

        $taskInfo->update([
            'task_status' => 'COMPLETE',
        ]);

        return response()->json($taskInfo);
    }

    public function remove($id)
    {
        $taskInfo = Task::findorFail($id);

        $taskInfo->update([
            'is_active' => '0',
        ]);

        return response()->json($taskInfo);
    }

    public function display_task($id)
    {
        $task = Task::with('user')
            ->where('task_i_information_id', $id)
            ->where('task_status', '=', 'PENDING')
            ->first();
        return response()->json([
            'task_i_information_id' => $task->task_i_information_id,
            's_bpartner_employee_id' => $task->s_bpartner_employee_id,
            'task_name'             => $task->task_name,
            'description'           => $task->description,
            'due_date'              => $task->due_date,
            'task_status'           => $task->task_status,
            'firstname' => $task->user->firstname,
            'middlename' => $task->user->middlename,
            'lastname'  => $task->user->lastname,
            'email'     => $task->user->email,
            'contact_no' => $task->user->contact_no,
            'address'   => $task->user->address,
        ]);
    }

    public function displayTaskInformation() {}

    public function filterTask(Request $request)
    {
        $query = Task::query();

        if ($request->filled('assigned')) {
            $query->where('s_bpartner_employee_id', $request->assigned);
        }

        if ($request->filled('type')) {
            $query->where('task_category', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('task_status', $request->status);
        }

        return response()->json($query->get());
    }
    public function pendingAndCompleteTasks($id)
    {
        $today = now()->startOfDay();
        $tasks = Task::with('DueDates')
            ->where('s_bpartner_employee_id', $id)
            ->get();

        $totalTasks = $tasks->count();

        $deadlineCount = $tasks->filter(function ($task) use ($today) {
            $latestDueDate = $task->DueDates->max('due_date');
            return $latestDueDate && $latestDueDate < $today;
        })->count();

        $activeCount = $totalTasks - $deadlineCount;

        $deadlinePercent = $totalTasks > 0 ? round(($deadlineCount / $totalTasks) * 100, 2) : 0;
        $activePercent   = $totalTasks > 0 ? round(($activeCount / $totalTasks) * 100, 2) : 0;
        $teamAccess = TeamAccess::select('supervisor_id', 's_bpartner_employee_id')->get()->groupBy('supervisor_id');
        $visited = [];

        $collectTeamMembers = function ($supervisorId) use (&$collectTeamMembers, &$visited, $teamAccess) {
            if (isset($visited[$supervisorId])) return [];
            $visited[$supervisorId] = true;
            $members = $teamAccess->get($supervisorId, collect());
            $all = [];
            foreach ($members as $m) {
                $all[] = $m->s_bpartner_employee_id;
                $all = array_merge($all, $collectTeamMembers($m->s_bpartner_employee_id));
            }
            return $all;
        };
        $teamMemberIds = $collectTeamMembers($id);
        $teamTasks = Task::with('DueDates')
            ->whereIn('s_bpartner_employee_id', $teamMemberIds)
            ->get();
        $teamTotal = $teamTasks->count();

        $teamDeadlineCount = $teamTasks->filter(function ($task) use ($today) {
            $latestDueDate = $task->DueDates->max('due_date');
            return $latestDueDate && $latestDueDate < $today;
        })->count();

        $teamActiveCount = $teamTotal - $teamDeadlineCount;

        $teamPendingDeadlinePercentage = $teamActiveCount > 0 ? round(($teamDeadlineCount / $teamTotal) * 100, 2) : 0;
        $teamActiveDeadlinePercentage   = $teamActiveCount > 0 ? round(($teamActiveCount / $teamTotal) * 100, 2) : 0;

        return response()->json([
            'DEADLINE' => $deadlinePercent,
            'ACTIVE'   => $activePercent,
            'Team_Total_Pending' => $teamPendingDeadlinePercentage,
            'Team_Total_Active'  => $teamActiveDeadlinePercentage
        ]);
    }

    public function teamProgress($id)
    {
        $today = now()->startOfDay();
        $teamAccess = TeamAccess::with('user')
            ->select('supervisor_id', 's_bpartner_employee_id')
            ->where('is_active', 1)
            ->get()
            ->groupBy('supervisor_id');
        $allTasks = Task::with('DueDates')
            ->select('s_bpartner_employee_id', 'task_i_information_id')
            ->get()
            ->groupBy('s_bpartner_employee_id');

        $visited = [];

        $buildTree = function ($supervisorId) use (&$buildTree, &$visited, $teamAccess, $allTasks, $today) {
            if (isset($visited[$supervisorId])) return [];
            $visited[$supervisorId] = true;

            $members = $teamAccess->get($supervisorId, collect());
            $nodes = [];

            foreach ($members as $member) {
                $memberUser = $member->user;
                if (!$memberUser) continue;

                $memberId = $memberUser->s_bpartner_employee_id;

                $tasks = $allTasks->get($memberId, collect());
                $ownTotal = $tasks->count();
                $ownPending = $tasks->filter(function ($task) use ($today) {
                    $latestDueDate = $task->DueDates->max('due_date');
                    return $latestDueDate && $latestDueDate < $today;
                })->count();
                $ownActive = $ownTotal - $ownPending;

                $deadlinePercent = $ownTotal > 0 ? round(($ownPending / $ownTotal) * 100, 2) : 0;
                $activePercent   = $ownTotal > 0 ? round(($ownActive / $ownTotal) * 100, 2) : 0;

                $children = $buildTree($memberId);

                $teamPending = 0;
                $teamActive  = 0;
                foreach ($children as $child) {
                    $teamPending += $child['OWN_PENDING'] + $child['TEAM_PENDING'];
                    $teamActive  += $child['OWN_ACTIVE'] + $child['TEAM_ACTIVE'];
                }

                $teamTotal = $teamPending + $teamActive;
                $teamPendingPercent = $teamTotal > 0 ? round(($teamPending / $teamTotal) * 100, 2) : 0;
                $teamActivePercent  = $teamTotal > 0 ? round(($teamActive / $teamTotal) * 100, 2) : 0;

                $nodes[] = [
                    's_bpartner_employee_id' => $memberId,
                    'name'     => $memberUser->firstname . ' ' . $memberUser->lastname,
                    'DEADLINE' => $deadlinePercent,
                    'ACTIVE'   => $activePercent,
                    'OWN_PENDING'  => $ownPending,
                    'OWN_ACTIVE'   => $ownActive,
                    'TEAM_PENDING' => $teamPending,
                    'TEAM_ACTIVE'  => $teamActive,
                    'TEAM_PENDING_PERCENT' => $teamPendingPercent,
                    'TEAM_ACTIVE_PERCENT'  => $teamActivePercent,
                    'members'  => $children
                ];
            }
            return $nodes;
        };
        return response()->json($buildTree($id));
    }

    private function getAllTeamMemberIds($id)
    {
        $ids = collect();
        $queue = collect([$id]);

        while ($queue->isNotEmpty()) {
            $currentId = $queue->shift();
            $team = TeamAccess::where('supervisor_id', $currentId)->pluck('s_bpartner_employee_id');
            $newIds = $team->diff($ids);
            $ids = $ids->merge($newIds);
            $queue = $queue->merge($newIds);
        }

        return $ids->unique()->values();
    }
}
