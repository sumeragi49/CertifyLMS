<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\QaReplyReserved;
use App\Notifications\QaReplyMessageNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendQaReplyNotification
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
    public function handle(QaReplyReserved $event): void
    {
        $recipient = $event->reply->thread->user;

        $recipient->notify(new QaReplyMessageNotification($event->reply));
    }
}
