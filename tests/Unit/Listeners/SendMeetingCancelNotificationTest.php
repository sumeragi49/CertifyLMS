<?php

namespace Tests\Unit\Listeners;

use App\Events\MeetingCancelReserved;
use App\Listeners\SendMeetingCancelNotification;
use App\Models\Meeting;
use App\Models\User;
use App\Notifications\MeetingCancelReservedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendMeetingCancelNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_sends_meeting_cancel_notification_sender_and_recipient(): void
    {
        Notification::fake();

        $sender = User::factory()->student()->create();
        $recipient = User::factory()->coach()->create();

        $meeting = Meeting::factory()->create([
            'coach_id' => $recipient->id,
            'student_id' => $sender->id,
        ]);

        $listener = new SendMeetingCancelNotification();
        $listener->handle(new MeetingCancelReserved($meeting));

        Notification::assertSentTo(
            [$sender,$recipient],
            MeetingCancelReservedNotification::class,
        );
    }
}
