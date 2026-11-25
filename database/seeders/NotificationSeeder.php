<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = User::where('email','student@certify-lms.test')
                -> first();

        $coach = User::where('email', 'coach@certify-lms.test')
              -> first();

        $param = [
            'id' => Str::ulid(),
            'type' => 'App\Notifications\ChatMessageNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $student->id,
            'data' => '{"notification_type":"chat_message_received","title":"チャットの返信","message":"新着のメッセージが届きました。"}',
            'created_at' => now(),
        ];
        DB::table('notifications')->insert($param);

        $param = [
            'id' => Str::ulid(),
            'type' => 'App\Notifications\QaReplyMessageNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $student->id,
            'data' => '{"notification_type":"qa_reply_received","title":"質問の返信","message":"質問の返答が届きました。"}',
            'created_at' => now(),
        ];
        DB::table('notifications')->insert($param);

        $param = [
            'id' => Str::ulid(),
            'type' => 'App\Notifications\MeetingBookedNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $student->id,
            'data' => '{"notification_type":"meeting_reserved","title":"面談の確定","message":"面談の予約が確定されました。"}',
            'created_at' => now(),
        ];
        DB::table('notifications')->insert($param);

        $param = [
            'id' => Str::ulid(),
            'type' => 'App\Notifications\MeetingBookedNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $student->id,
            'data' => '{"notification_type":"meeting_canceled","title":"面談のキャンセル","message":"面談がキャンセルされました。"}',
            'created_at' => now(),
        ];
        DB::table('notifications')->insert($param);

    $param = [
            'id' => Str::ulid(),
            'type' => 'App\Notifications\MeetingBookedNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $coach->id,
            'data' => '{"notification_type":"meeting_reserved","title":"面談の確定","message":"面談の予約が確定されました。"}',
            'created_at' => now(),
        ];
        DB::table('notifications')->insert($param);

        $param = [
            'id' => Str::ulid(),
            'type' => 'App\Notifications\MeetingBookedNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $coach->id,
            'data' => '{"notification_type":"meeting_canceled","title":"面談のキャンセル","message":"面談がキャンセルされました。"}',
            'created_at' => now(),
        ];
        DB::table('notifications')->insert($param);

        $param = [
            'id' => Str::ulid(),
            'type' => 'App\Notifications\QaThreadNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $coach->id,
            'data' => '{"notification_type":"completion_approved","title":"問題の解決","message":"質問が解決状態になりました。"}',
            'created_at' => now(),
        ];
        DB::table('notifications')->insert($param);
    }
}
