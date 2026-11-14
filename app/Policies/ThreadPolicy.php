<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\QaThread;
use Illuminate\Auth\Access\Response;

class ThreadPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //return $user->role === UserRole::Student;
        return in_array($user->role, [UserRole::Student, UserRole::Coach], true);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Coach, UserRole::Student], true);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Student], true);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, QaThread $thread): bool
    {
        return $this->canManage($user, $thread);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, QaThread $thread): bool
    {
        return $this->canManage($user, $thread);
    }

    /**
     * Determine whether the user can resolve the model.
     */
    public function resolve(User $user, QaThread $thread): bool
    {
        return $this->canManage($user, $thread);
    }

    /**
     * Determine whether the user can unresolve the model.
     */
    public function unResolve(User $user, QaThread $thread): bool
    {
        return $this->canManage($user, $thread);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Thread $thread): bool
    {
        //
    }

    private function canManage(User $student, QaThread $thread): bool
    {
        return match ($student->role) {
            UserRole::Student => true
        };
    }

    private function createdStudent(User $student, QaThread $thread): bool
    {
        return $thread->user()->where('users.id', $student->id)->exists();
    }
}
