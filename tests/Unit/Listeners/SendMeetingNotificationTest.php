<?php

namespace Tests\Unit\Listeners;

use App\Events\MeetingReserved;
use App\Listeners\SendMeetingNotification;
use App\Models\Meeting;
use App\Models\User;
use App\Notifications\MeetingReservedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendMeetingNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_sends_meeting_notification_sender_and_recipient(): void
    {
        Notification::fake();

        $sender = User::factory()->student()->create();
        $recipient = User::factory()->coach()->create();

        $meeting = Meeting::factory()->create([
            'coach_id' => $recipient->id,
            'student_id' => $sender->id,
        ]);

        $listener = new SendMeetingNotification();
        $listener->handle(new MeetingReserved($meeting));

        Notification::assertSentTo(
            [$sender,$recipient],
            MeetingReservedNotification::class,
        );
    }
}
