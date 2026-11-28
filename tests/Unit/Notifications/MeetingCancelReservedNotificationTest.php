<?php

namespace Tests\Unit\Notifications;

use App\Models\User;
use App\Models\Meeting;
use App\Notifications\MeetingCancelReservedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class MeetingCancelReservedNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_to_mail_has_meeting_cancel_greeting(): void
    {
        // Arrange
        $user = User::factory()->create();
        $meeting = Meeting::factory()->create();
        $notification = new MeetingCancelReservedNotification($meeting);

        // Act
        $mail = $notification->toMail($user);

        // Assert
        $this->assertInstanceOf(MailMessage::class, $mail);
        $this->assertSame('こんにちは！', $mail->greeting);
    }

    public function test_to_database_has_meeting_cancel(): void
    {
        $user = User::factory()->create();
        $meeting = Meeting::factory()->create();

        $user -> notify(new MeetingCancelReservedNotification($meeting));

        $this->assertDatabaseHas('notifications',[
            'notifiable_id' => $user->id,
            'type' => 'App\Notifications\MeetingCancelReservedNotification',
        ]);
    }
}
