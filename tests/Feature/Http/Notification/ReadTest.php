<?php

namespace Tests\Feature\Http\Notification;

use App\Models\User;
use App\Models\InfoNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReadTest extends TestCase
{
    public function test_student_can_update_read_at(): void
    {
        $student = User::factory()->student()->create();

        $notification = InfoNotification::factory()->create([
            'notifiable_id' => $student->id,
            'data' => [
                "notification_type" => "chat_message_received",
                "title" => "チャットの返信",
                "message" => "新着のメッセージが届きました。"
            ],
        ]);

        $response = $this->actingAs($student)->post(route('notifications.markAsRead', $notification));

        $response->assertRedirect();
        $this->assertDatabaseHas('notifications', [
            "read_at" => $notification->read_at,
        ]);
    }

    public function test_coach_can_update_read_at(): void
    {
        $coach = User::factory()->coach()->create();

        $notification = InfoNotification::factory()->create([
            'notifiable_id' => $coach->id,
            'data' => [
                "notification_type" => "chat_message_received",
                "title" => "チャットの返信",
                "message" => "新着のメッセージが届きました。"
            ],
        ]);

        $response = $this->actingAs($coach)->post(route('notifications.markAsRead', $notification));

        $response->assertRedirect();
        $this->assertDatabaseHas('notifications', [
            "read_at" => $notification->read_at,
        ]);
    }

    public function test_admin_can_update_read_at(): void
    {
        $admin = User::factory()->admin()->create();

        $notification = InfoNotification::factory()->create([
            'notifiable_id' => $admin->id,
            'data' => [
                "notification_type" => "chat_message_received",
                "title" => "チャットの返信",
                "message" => "新着のメッセージが届きました。"
            ],
        ]);

        $response = $this->actingAs($admin)->post(route('notifications.markAsRead', $notification));

        $response->assertForbidden();
    }
}
