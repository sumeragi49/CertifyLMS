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
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, QaReply $qaReply): bool
    {
        return $this->canManage($user, $reply);
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
        return $this->canManage($user, $reply);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, QaReply $reply): bool
    {
        return $this->canManage($user, $reply);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, QaReply $qaReply): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, QaReply $qaReply): bool
    {
        //
    }

    private function canManage(User $user, QaReply $reply): bool
    {
        return match ($user->role) {
            UserRole::Student => true,
            UserRole::Coach => true,
            UserRole::Admin => false,
        };
    }
}
