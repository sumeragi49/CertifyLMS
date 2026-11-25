<?php

namespace Database\Factories;

use App\Models\Uesr;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $notificationType = fake()->randomElement([
            'chat_message_received',
            'qa_reply_received',
            'completion_approved',
            'meeting_reserved',
            'meeting_canceled',
            'meeting_reminder',
            'admin_announcement',
            default,
        ]);

        $title = match ($notificationType) {
            'chat_message_received' => 'チャットの返信',
            'qa_reply_received' => '質問の返信',
            'completion_approved' => '問題の解決',
            'meeting_reserved' => '面談の確定',
            'meeting_canceled' => '面談のキャンセル',
            'meeting_reminder' => '面談の時間',
            'admin_announcement' => '運営の通知',
            default => '通知',
        };

        $message = match ($notificationType) {
            'chat_message_received' => '新着のメッセージが届きました。',
            'qa_reply_received' => '質問の返答が届きました。',
            'completion_approved' => '質問が解決状態になりました。',
            'meeting_reserved' => '面談の予約が確定されました。',
            'meeting_canceled' => '面談がキャンセルされました。',
            'meeting_reminder' => 'まもなく予約した面談の時間です。',
            'admin_announcement' => '運営からのお知らせがあります。',
            default  => '新しい通知があります。',
        };

        return [
            'id' => (string) Str::ulid(),
            'type' => DatabaseNotification::class,
            'notifiable_type' => User::class,
            'notifiable_id' => User::factory(),
            'data' => json_encode([
                'notification_type' => $notificationType,
                'title' => $title,
                'body' => $message,
            ]),
            'read_at' => null,
        ];
    }
}
