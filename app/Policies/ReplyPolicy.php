<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\QaThread;
use App\Models\QaReply;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReplyPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, QaReply $reply): bool
    {
        return $user->id === $reply->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Coach, UserRole::Student], true);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, QaReply $reply): bool
    {
        return $user->id === $reply->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, QaReply $reply): bool
    {
        return $user->id === $reply->user_id || in_array($user->role, [UserRole::Admin], true);
    }
}
