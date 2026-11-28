<?php

namespace Tests\Unit\Notifications;

use App\Models\User;
use App\Models\QaReply;
use App\Notifications\QaReplyMessageNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class QaReplyMessageNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_to_mail_has_reply_greeting(): void
    {
        // Arrange
        $user = User::factory()->create();
        $reply = QaReply::factory()->create();
        $notification = new QaReplyMessageNotification($reply);

        // Act
        $mail = $notification->toMail($user);

        // Assert
        $this->assertInstanceOf(MailMessage::class, $mail);
        $this->assertSame('こんにちは！', $mail->greeting);
    }

    public function test_to_database_has_chat_message(): void
    {
        $user = User::factory()->create();
        $reply = QaReply::factory()->create();

        $user -> notify(new QaReplyMessageNotification($reply));

        $this->assertDatabaseHas('notifications',[
            'notifiable_id' => $user->id,
            'type' => 'App\Notifications\QaReplyMessageNotification',
        ]);
    }
}
