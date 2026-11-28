<?php

namespace Tests\Feature\Http\Notification;

use App\Models\User;
use App\Models\InfoNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReadAllTest extends TestCase
{
    public function test_student_can_update_read_at_all_notification(): void
    {
        $student = User::factory()->student()->create();

        $notification1 = InfoNotification::factory()->create([
            'notifiable_id' => $student->id,
            'data' => [
                "notification_type" => "chat_message_received",
                "title" => "チャットの返信",
                "message" => "新着のメッセージが届きました。"
            ],
        ]);

        $notification2 = InfoNotification::factory()->create([
            'notifiable_id' => $student->id,
            'data' => [
                "notification_type" => "qa_reply_received",
                "title" => "質問の返信",
                "message" => "質問の返答が届きました。"
            ],
        ]);

        $response = $this->actingAs($student)->post(route('notifications.markAllAsRead'));

        $response->assertRedirect();
        $this->assertDatabaseHas('notifications', [
            "read_at" => $notification1->read_at,
            "read_at" => $notification2->read_at,
        ]);
    }

    public function test_coach_can_update_read_at_all_notification(): void
    {
        $coach = User::factory()->coach()->create();

        $notification1 = InfoNotification::factory()->create([
            'notifiable_id' => $coach->id,
            'data' => [
                "notification_type" => "chat_message_received",
                "title" => "チャットの返信",
                "message" => "新着のメッセージが届きました。"
            ],
        ]);

        $notification2 = InfoNotification::factory()->create([
            'notifiable_id' => $coach->id,
            'data' => [
                "notification_type" => "qa_reply_received",
                "title" => "質問の返信",
                "message" => "質問の返答が届きました。"
            ],
        ]);

        $response = $this->actingAs($coach)->post(route('notifications.markAllAsRead'));

        $response->assertRedirect();
        $this->assertDatabaseHas('notifications', [
            "read_at" => $notification1->read_at,
            "read_at" => $notification2->read_at,
        ]);
    }

    public function test_admin_cannot_update_read_at_all_notification(): void
    {
        $admin = User::factory()->admin()->create();

        $notification1 = InfoNotification::factory()->create([
            'notifiable_id' => $admin->id,
            'data' => [
                "notification_type" => "chat_message_received",
                "title" => "チャットの返信",
                "message" => "新着のメッセージが届きました。"
            ],
        ]);

        $notification2 = InfoNotification::factory()->create([
            'notifiable_id' => $admin->id,
            'data' => [
                "notification_type" => "qa_reply_received",
                "title" => "質問の返信",
                "message" => "質問の返答が届きました。"
            ],
        ]);

        $response = $this->actingAs($admin)->post(route('notifications.markAllAsRead'));

        $response->assertForbidden();
    }
}
