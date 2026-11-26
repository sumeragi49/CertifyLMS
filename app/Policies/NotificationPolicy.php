<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\InfoNotification;

class NotificationPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Student, UserRole::Coach], true);
    }

    public function read(User $user, InfoNotification $notification): bool
    {
        return $user->id === $notification->notifiable_id;
    }
}
