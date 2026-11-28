<?php

namespace App\Listeners;

use App\Events\MeetingReserved;
use App\Notifications\MeetingReservedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMeetingNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MeetingReserved $event): void
    {
        $studentRecipient = $event->meeting->student;

        $studentRecipient->notify(new MeetingReservedNotification($event->meeting));

        $coachRecipient = $event->meeting->coach;

        $coachRecipient->notify(new MeetingReservedNotification($event->meeting));
    }
}
