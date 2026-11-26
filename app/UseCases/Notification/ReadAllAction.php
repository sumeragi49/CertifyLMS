<?php

declare(strict_types=1);

namespace App\UseCases\Notification;

use App\Models\InfoNotification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

final class ReadAllAction
{
    public function __invoke(): Collection
    {
        $user = auth()->user();

        $notifications = InfoNotification::query()
                      -> where('notifiable_id',$user->id)
                      -> where('read_at', null)
                      -> get();

        return DB::transaction(function () use ($notifications) {
            $notifications->each->update([
                'read_at' => now()
            ]);

            return $notifications->fresh();
        });
    }
}