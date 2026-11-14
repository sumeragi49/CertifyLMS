<?php

declare(strict_types=1);

namespace App\UseCases\Thread;

use App\Enums\QaThreadStatus;
use App\Models\QaThread;
use Illuminate\Support\Facades\DB;

final class UnresolveAction
{
    public function __invoke(QaThread $thread): QaThread
    {
        return DB::transaction(function () use ($thread) {
            $thread->update([
            'status' => QaThreadStatus::Unresolved->value,
            ]);

            return $thread->fresh();
        });
    }
}