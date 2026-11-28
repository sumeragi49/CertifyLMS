<?php

namespace Tests\Unit\Listeners;

use App\Events\QaReplyReserved;
use App\Listeners\SendQaReplyNotification;
use App\Models\QaReply;
use App\Models\QaThread;
use App\Models\User;
use App\Notifications\QaReplyMessageNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendQaReplyNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_sends_notification_only_to_question_owner(): void
    {
        Notification::fake();

        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $thread = QaThread::factory()->create([
            'user_id' => $recipient,
        ]);
        $reply = QaReply::factory()->create([
            'qa_thread_id' => $thread->id,
            'user_id' => $sender,
        ]);

        $listener = new SendQaReplyNotification();
        $listener->handle(new QaReplyReserved($reply));

        Notification::assertSentTo(
            [$recipient],
            QaReplyMessageNotification::class,
        );

        Notification::assertNotSentTo([$sender], QaReplyMessageNotification::class);
    }
}
