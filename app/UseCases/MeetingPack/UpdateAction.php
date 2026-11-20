<?php

declare(strict_types=1);

namespace App\UseCases\MeetingPack;

use App\Models\MeetingPack;
use Illuminate\Support\Facades\DB;

final class UpdateAction
{
    public function __invoke(MeetingPack $plan, array $validated): MeetingPack
    {
        return DB::transaction(function () use ($plan, $validated) {
            $plan->update($validated);

            return $plan->fresh();
        });
    }
}