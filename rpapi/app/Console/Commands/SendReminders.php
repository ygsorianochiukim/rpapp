<?php

namespace App\Console\Commands;

use App\Models\RemindTask;
use App\Models\UserAccount;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendReminders extends Command
{
    protected $signature = 'notifications:send-reminders';
    protected $description = 'Send push notifications for reminders';

    public function handle()
    {
        $now = Carbon::now()->format('Y-m-d H:i:00');

        $reminders = RemindTask::where('date_remind', $now)
            ->where('is_active', 1)
            ->get();

        foreach ($reminders as $reminder) {
            $user = UserAccount::find($reminder->created_by);

            if ($user && $user->player_id) {
                $this->sendPush($user->player_id, "Reminder", "Task reminder: {$reminder->remind_task}");
            }
        }

        return 0;
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
