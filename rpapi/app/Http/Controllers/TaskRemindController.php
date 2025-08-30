<?php

namespace App\Http\Controllers;

use App\Models\RemindTask;
use App\Models\TaskNotes;
use App\Models\UserAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TaskRemindController extends Controller
{
    public function displayReminders(){
        $displayDueRepeat = RemindTask::all();

        return response()->json($displayDueRepeat);
    }

    public function newReminders(Request $request){
        $dueDateReminder = $request->validate([
            "task_i_information_id" => "required",
            "remind_task" => "string|required",
            "created_by" => "required",
        ]);

        $reminder = $request->remind_task;

        if($reminder == 'Tomorrow'){
            $dueDateReminder['date_remind'] = date('Y-m-d H:i:s', strtotime('+1 day'));
        }
        else if($reminder == 'Next Week'){
            $dueDateReminder['date_remind'] = date('Y-m-d H:i:s', strtotime('+7 day'));
        }
        else if($reminder == 'Later Today'){
            $dueDateReminder['date_remind'] = date('Y-m-d H:i:s');
        }
        $dueDateReminder['is_Active'] = '1';
        $dueDateReminder['created_date'] = date('Y-m-d H:i:s');
        $DueFieldRemind = RemindTask::create($dueDateReminder);
        return response()->json(['Submit Reminder' , $DueFieldRemind]);
    }

    public function display_task_user($id){
        $step = RemindTask::where('task_i_information_id', '=', $id)->get();
        return response()->json($step);
    }

    public function send()
    {
        $now = Carbon::now()->format('Y-m-d H:i:00');

        $reminders = RemindTask::where('date_remind', $now)
            ->where('is_Active', 1)
            ->get();

        foreach ($reminders as $reminder) {
            $user = UserAccount::find($reminder->created_by);

            if ($user && $user->player_id) {
                $this->sendPush(
                    $user->player_id,
                    "Reminder",
                    "Task reminder: {$reminder->remind_task}"
                );
            }
        }

        return response()->json(['done' => true]);
    }

    private function sendPush($playerId, $title, $message)
    {
        Http::withHeaders([
            'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => env('ONESIGNAL_APP_ID'),
            'include_player_ids' => [$playerId],
            'headings' => ["en" => $title],
            'contents' => ["en" => $message],
        ]);
    }
}
