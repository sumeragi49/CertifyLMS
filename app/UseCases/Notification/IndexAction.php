<?php

declare(strict_types=1);

namespace App\UseCases\Notification;

use App\Models\User;
use App\Models\InfoNotification;
use Illuminate\Database\Eloquent\Collection;

final class IndexAction
{
    /**
     * @return Collection<int, CertificationCategory>
     */
    public function __invoke(User $user, array $tabs = []): array
    {
        $tab = $tabs['tab'] ?? null;

        if ($tab === 'unread') {
            $notifications = InfoNotification::query()
               ->where('notifiable_id',$user->id)
               ->where('read_at', null)
               ->paginate(10);
        } else {
            $notifications = InfoNotification::query()
               ->where('notifiable_id',$user->id)
               ->paginate(10);
        }

        $unreadCount = InfoNotification::query()
                     -> where('notifiable_id',$user->id)
                     -> where('read_at', null)
                     -> count();

        $tabs = [
            'all' => '全件',
            'unread' => '未読のみ',
        ];

        return [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'tabs' => $tabs,
        ];
    }
}