<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserAccessController;
use App\Http\Controllers\AccessLineController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AppVersionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\StepTaskController;
use App\Http\Controllers\SyncSheetController;
use App\Http\Controllers\TaskBankController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskDueController;
use App\Http\Controllers\TaskFileController;
use App\Http\Controllers\TaskLogsController;
use App\Http\Controllers\TaskNoteController;
use App\Http\Controllers\TaskRemindController;
use App\Http\Controllers\TaskRepeatController;
use App\Http\Controllers\TeamAccessController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::get('/hr-dashboard', function () {
    return response()->json(['message' => 'Welcome HR']);
})->middleware(['auth:sanctum', 'position:HR']);

Route::get('/userAccount', [UserAccountController::class,'display_user']);
Route::post('/userAccount', [UserAccountController::class,'add_user_account']);
Route::get('/userAccount/displayUser/{id}', [UserAccountController::class,'displayUserTask']);
Route::post('/userAccount/sentOTP/{id}', [UserAccountController::class,'sendOtp']);
Route::get('/userAccount/personalInfo/{id}', [UserAccountController::class,'displayPersonalUser']);
Route::post('/userAccount/password/{id}', [UserAccountController::class, 'updatePassword']);

Route::post('/position', [PositionController::class,'newPosition']);
Route::get('/position', [PositionController::class,'displayPosition']);

Route::get('/userAccess', [UserAccessController::class,'displayUserAccess']);
Route::post('/userAccess', [UserAccessController::class,'addNewAccess']);

Route::get('/accessLine', [AccessLineController::class,'displayAccessLine']);
Route::post('/accessLine', [AccessLineController::class,'addAccessLine']);

Route::get('/taskLogs', [TaskLogsController::class,'displayLogs']);
Route::post('/taskLogs', [TaskLogsController::class,'addTaskLogs']);
Route::get('/taskLogs/complete', [TaskLogsController::class,'displayComplete']);
Route::get('/taskLogs/complete/{id}', [TaskLogsController::class,'displayCompleteTaskPerUser']);

Route::get('/teamAccess', [TeamAccessController::class,'displayTeam']);
Route::get('/teamAccess/list/{id}', [TeamAccessController::class,'listMyTeam']);
Route::post('/teamAccess', [TeamAccessController::class,'addNewTeam']);
Route::get('/teamAccess/dropdown/{id}', [TeamAccessController::class,'dropDownItem']);
Route::patch('/teamAccess/{id}/remove', [TeamAccessController::class, 'removeMember']);

Route::get('/task', [TaskController::class,'displayTask']);
Route::post('/task', [TaskController::class,'addTask']);
Route::get('/task/displayTask/{id}', [TaskController::class,'display_task']);
Route::get('/task/filter', [TaskController::class, 'filterTask']);
Route::get('/task/chart/{id}', [TaskController::class, 'pendingAndCompleteTasks']);
Route::get('/task/dues', [TaskController::class, 'TaskDueDates']);
Route::get('/task/UserTask/{id}', [TaskController::class, 'displayUsersTask']);

Route::get('/task/progress/{id}', [TaskController::class, 'teamProgress']);
Route::put('/task/DoneTask/{id}', [TaskController::class, 'markasDone']);

Route::put('/task/removeTask/{id}', [TaskController::class, 'remove']);

Route::get('/steptask', [StepTaskController::class,'displayStepTask']);
Route::post('/steptask', [StepTaskController::class,'newStepTask']);
Route::get('/steptask/displayUserTask/{id}', [StepTaskController::class,'display_task_user']);

Route::get('/taskFile', [TaskFileController::class,'displayTaskFile']);
Route::post('/taskFile', [TaskFileController::class,'addTaskFile']);
Route::get('/taskFile/displayUserTask/{id}', [TaskFileController::class,'display_task_user']);
Route::get('/taskFile/download/{id}', [TaskFileController::class, 'downloadTaskFile']);

Route::get('/notetask', [TaskNoteController::class,'displayNote']);
Route::post('/notetask', [TaskNoteController::class,'addNewNotes']);
Route::get('/notetask/displayUserTask/{id}', [TaskNoteController::class,'display_task_user']);

Route::get('/dueReminder', [TaskRemindController::class,'displayReminders']);
Route::post('/dueReminder', [TaskRemindController::class,'newReminders']);
Route::get('/dueReminder/displayUserTask/{id}', [TaskRemindController::class,'display_task_user']);
Route::get('/send-reminders', [TaskRemindController::class, 'send']);

Route::get('/dueRepeat', [TaskRepeatController::class,'displayRepeat']);
Route::post('/dueRepeat', [TaskRepeatController::class,'newDueDateRepeat']);
Route::get('/dueRepeat/displayUserTask/{id}', [TaskRepeatController::class,'display_task_user']);

Route::get('/taskDue', [TaskDueController::class,'displayTaskDue']);
Route::post('/taskDue', [TaskDueController::class,'newDueDate']);
Route::post('/taskDue/complete/{id}', [TaskDueController::class,'updatetaskDue']);
Route::get('/taskDue/displayUserTask/{id}', [TaskDueController::class,'display_task_user']);

Route::get('/announcement', [AnnouncementController::class, 'displayAnnouncements']);
Route::post('/announcement', [AnnouncementController::class, 'addNewAnnouncement']);

Route::post('/save-player-id', [PlayerController::class, 'save']);

Route::post('/check-version', [AppVersionController::class, 'checkVersion']);

Route::post('/syncUpdate', [SyncSheetController::class, 'SyncSheetUpdate']);

Route::get('/task-bank', [TaskBankController::class, 'index']);
Route::post('/task-bank', [TaskBankController::class, 'store']);
Route::put('/task-bank/{id}', [TaskBankController::class, 'update']);
Route::delete('/task-bank/{id}', [TaskBankController::class, 'destroy']);
Route::post('/task-bank/sync-by-position', [TaskBankController::class, 'syncByPosition']);