<?php

declare(strict_types=1);

namespace App\UseCases\MeetingPack;

use App\Enums\MeetingPackStatus;
use App\Models\User;
use App\Models\MeetingPack;
use Illuminate\Support\Facades\DB;

final class StoreAction
{
    public function __invoke(User $admin, array $validated): MeetingPack
    {
        return DB::transaction(fn () => MeetingPack::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'meeting_count' => $validated['meeting_count'],
            'price' => $validated['price'],
            'stripe_price_id' => $validated['stripe_price_id'],
            'status' => MeetingPackStatus::Draft->value,
            'sort_order' => $validated['sort_order'],
            'created_by_user_id' => $admin->id,
            'updated_by_user_id' => $admin->id,
        ]));
    }
}