<?php

namespace App\Notifications;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class MeetingCancelReservedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Meeting $meeting,
    ){
        $this->id = (string) Str::ulid();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $role = $notifiable->role instanceof \BackedEnum
            ? $notifiable->role->value
            : $notifiable->role;

        if ($role === 'student') {
            $url = route('meetings.index');
        } else {
            $url = route('coach.meetings.index');
        }

        return (new MailMessage)
                    ->greeting('こんにちは！')
                    ->line('面談の予約がキャンセルされました。')
                    ->action('面談予約キャンセルの確認', $url)
                    ->line('ご確認ください。');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $role = $notifiable->role instanceof \BackedEnum
            ? $notifiable->role->value
            : $notifiable->role;

        if ($role === 'student') {
            $url = route('meetings.index');
        } else {
            $url = route('coach.meetings.index');
        }

        return [
            'notification_type' => 'meeting_canceled',
            'title' => '面談予約のキャンセル',
            'message' => '予約されていた面談がキャンセルされました。確認してください。',
            'url' => $url,
        ];
    }
}
