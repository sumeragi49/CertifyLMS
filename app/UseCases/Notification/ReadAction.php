<?php

declare(strict_types=1);

namespace App\UseCases\Notification;

use App\Models\InfoNotification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

final class ReadAction
{
    public function __invoke(InfoNotification $notification): InfoNotification
    {
        return DB::transaction(function () use ($notification) {
            $notification->update([
                'read_at' => now()
            ]);

            return $notification->fresh();
        });
    }
}