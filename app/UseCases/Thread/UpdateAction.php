<?php

declare(strict_types=1);

namespace App\UseCases\Thread;

use App\Models\QaThread;
use Illuminate\Support\Facades\DB;

final class UpdateAction
{
    public function __invoke(QaThread $thread, array $validated): QaThread
    {
        return DB::transaction(function () use ($thread, $validated) {
            $thread->update($validated);

            return $thread->fresh();
        });
    }
}
