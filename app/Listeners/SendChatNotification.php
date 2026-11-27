<?php

namespace App\Listeners;

use App\Events\ChatMessageSent;
use App\Models\User;
use App\Models\InfoNotification;
use App\Notifications\ChatMessageNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendChatNotification
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
    public function handle(ChatMessageSent $event): void
    {
        $user = $event->message->sender;

        $recipients = User::query()
                  -> with('chatMembers')
                  -> whereHas('chatMembers',
                         fn ($q) => $q->where('chat_room_id', $event->message->chat_room_id)
                         ->where('user_id', '!=', $event->message->sender->id,)
                  )
                  -> get();
                
        foreach ($recipients as $recipient) {
            $recipient->notify(new ChatMessageNotification($event->message));
        }
    }
}
