<?php

declare(strict_types=1);

namespace App\UseCases\Plan;

use App\Enums\PlanStatus;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;

final class StoreAction
{
    public function __invoke(User $admin, array $validated): Plan
    {
        return DB::transaction(fn () => Plan::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'duration_days' => $validated['duration_days'],
            'default_meeting_quota' => $validated['default_meeting_quota'],
            'status' => PlanStatus::Draft->value,
            'sort_order' => $validated['sort_order'],
            'created_by_user_id' => $admin->id,
            'updated_by_user_id' => $admin->id,
        ]));
    }
}