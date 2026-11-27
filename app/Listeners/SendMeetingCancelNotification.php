<?php

namespace App\Listeners;

use App\Events\MeetingCancelReserved;
use App\Notifications\MeetingCancelReservedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMeetingCancelNotification
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
    public function handle(MeetingCancelReserved $event): void
    {
        $studentRecipient = $event->meeting->student;

        $studentRecipient->notify(new MeetingCancelReservedNotification($event->meeting));

        $coachRecipient = $event->meeting->coach;

        $coachRecipient->notify(new MeetingCancelReservedNotification($event->meeting));
    }
}
