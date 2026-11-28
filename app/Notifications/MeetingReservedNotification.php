<?php

namespace App\Notifications;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class MeetingReservedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Meeting $meeting)
    {
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
        if ($notifiable->role === 'student') {
            $url = route('meetings.index');
        } elseif ($notifiable->role === 'coach') {
            $url = route('coach.meetings.index');
        } else {
            $url = route('dashboard.index');
        }

        return (new MailMessage)
                    ->greeting('こんにちは！')
                    ->line('面談の予約が確定しました。')
                    ->action('面談予約の確認', $url)
                    ->line('ご確認ください。');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if ($notifiable->role === 'student') {
            $url = route('meetings.index');
        } elseif ($notifiable->role === 'coach') {
            $url = route('coach.meetings.index');
        } else {
            $url = route('dashboard.index');
        }

        return [
            'notification_type' => 'meeting_received',
            'title' => '面談予約の確定',
            'message' => '面談の日付が確定しました。確認してください。',
            'url' => $url,
        ];
    }
}
