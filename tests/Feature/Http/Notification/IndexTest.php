<?php

namespace Tests\Feature\Http\Notification;

use App\Models\User;
use App\Models\InfoNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class IndexTest extends TestCase
{
     public function test_validation_passes_with_all_notifications(): void
    {
        $student = User::factory()->student()->create();

        $studentNotification = InfoNotification::factory()->create([
            'notifiable_id' => $student->id,
            'data' => [
                "notification_type" => "chat_message_received",
                "title" => "チャットの返信",
                "message" => "新着のメッセージが届きました。"
            ],
        ]);

        $otherNotification = InfoNotification::factory()->create([
            'notifiable_id' => $student->id,
            'data' => [
                "notification_type" => "qa_reply_received",
                "title" => "質問の返信",
                "message" => "質問の返答が届きました。"
            ],
            'read_at' => now(),
        ]);

        $response = $this->actingAs($student)->get(route('notifications.index'));

        $response->assertSuccessful();
        $response->assertSee('チャットの返信');
        $response->assertSee('質問の返信');
    }
}
