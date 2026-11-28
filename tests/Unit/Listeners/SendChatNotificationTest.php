<?php

namespace Tests\Unit\Listeners;

use App\Events\ChatMessageSent;
use App\Listeners\SendChatNotification;
use App\Models\ChatMember;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use App\Notifications\ChatMessageNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendChatNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_sends_notification_only_to_other_members_in_same_room(): void
    {
        Notification::fake();

        $targetRoom = ChatRoom::factory()->create();
        $otherRoom = ChatRoom::factory()->create();

        $sender = User::factory()->create();
        $recipient1 = User::factory()->create();
        $recipient2 = User::factory()->create();
        $otherRoomUser = User::factory()->create();

        ChatMember::factory()->create(['chat_room_id' => $targetRoom->id, 'user_id' => $sender->id]);
        ChatMember::factory()->create(['chat_room_id' => $targetRoom->id, 'user_id' => $recipient1->id]);
        ChatMember::factory()->create(['chat_room_id' => $targetRoom->id, 'user_id' => $recipient2->id]);
        ChatMember::factory()->create(['chat_room_id' => $otherRoom->id, 'user_id' => $otherRoomUser->id]);

        $message = ChatMessage::factory()->create([
            'chat_room_id' => $targetRoom->id,
            'sender_user_id' => $sender->id,
        ]);

        $listener = new SendChatNotification();
        $listener->handle(new ChatMessageSent($message));

        Notification::assertSentTo(
            [$recipient1, $recipient2],
            ChatMessageNotification::class,
        );

        Notification::assertNotSentTo([$sender, $otherRoomUser], ChatMessageNotification::class);
    }
}
