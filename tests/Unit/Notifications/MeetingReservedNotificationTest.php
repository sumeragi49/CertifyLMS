<?php

namespace Tests\Unit\Notifications;

use App\Models\User;
use App\Models\Meeting;
use App\Notifications\MeetingReservedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class MeetingReservedNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_to_mail_has_meeting_greeting(): void
    {
        // Arrange
        $user = User::factory()->create();
        $meeting = Meeting::factory()->create();
        $notification = new MeetingReservedNotification($meeting);

        // Act
        $mail = $notification->toMail($user);

        // Assert
        $this->assertInstanceOf(MailMessage::class, $mail);
        $this->assertSame('こんにちは！', $mail->greeting);
    }

    public function test_to_database_has_meeting(): void
    {
        $user = User::factory()->create();
        $meeting = Meeting::factory()->create();

        $user -> notify(new MeetingReservedNotification($meeting));

        $this->assertDatabaseHas('notifications',[
            'notifiable_id' => $user->id,
            'type' => 'App\Notifications\MeetingReservedNotification',
        ]);
    }
}
