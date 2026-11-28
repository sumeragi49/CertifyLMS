<?php

namespace App\Listeners;

use App\Events\ChatMessageSent;
use App\Models\User;
use App\Notifications\ChatMessageNotification;

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
