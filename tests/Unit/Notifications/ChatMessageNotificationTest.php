<?php

namespace Tests\Unit\Notifications;

use App\Models\User;
use App\Models\ChatMessage;
use App\Notifications\ChatMessageNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class ChatMessageNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_to_mail_has_chat_message_greeting(): void
    {
        // Arrange
        $user = User::factory()->create();
        $message = ChatMessage::factory()->create();
        $notification = new ChatMessageNotification($message);

        // Act
        $mail = $notification->toMail($user);

        // Assert
        $this->assertInstanceOf(MailMessage::class, $mail);
        $this->assertSame('こんにちは！', $mail->greeting);
    }

    public function test_to_database_has_chat_message(): void
    {
        $user = User::factory()->create();
        $message = ChatMessage::factory()->create();

        $user -> notify(new ChatMessageNotification($message));

        $this->assertDatabaseHas('notifications',[
            'notifiable_id' => $user->id,
            'type' => 'App\Notifications\ChatMessageNotification',
        ]);
    }
}
