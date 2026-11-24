<?php

declare(strict_types=1);

namespace App\UseCases\Plan;

use App\Models\Plan;
use Illuminate\Support\Facades\DB;

final class UpdateAction
{
    public function __invoke(Plan $plan, array $validated): plan
    {
        return DB::transaction(function () use ($plan, $validated) {
            $plan->update($validated);

            return $plan->fresh();
        });
    }
}