<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\CertificationCoachAttached;
use App\Events\CertificationCoachDetached;
use App\Events\ChatMessageSent;
use App\Events\QaReplyReserved;
use App\Events\MeetingReserved;
use App\Listeners\SyncChatMembersOnCoachAssignmentChanged;
use App\Listeners\UpdateLastLoginAt;
use App\Listeners\SendChatNotification;
use App\Listeners\SendQaReplyNotification;
use App\Listeners\SendMeetingNotification;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            UpdateLastLoginAt::class,
        ],
        CertificationCoachAttached::class => [
            SyncChatMembersOnCoachAssignmentChanged::class,
        ],
        CertificationCoachDetached::class => [
            SyncChatMembersOnCoachAssignmentChanged::class,
        ],
        ChatMessageSent::class => [
            SendChatNotification::class,
        ],
        QaReplyReserved::class => [
            SendQaReplyNotification::class,
        ],
        MeetingReserved::class => [
            SendMeetingNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
