<?php

declare(strict_types=1);

namespace App\UseCases\Thread;

use App\Enums\QaThreadStatus;
use App\Models\QaThread;
use Illuminate\Support\Facades\DB;

final class ResolveAction
{
    public function __invoke(QaThread $thread): QaThread
    {
        return DB::transaction(function () use ($thread) {
            $thread->update([
            'status' => QaThreadStatus::Resolved->value,
            ]);

            return $thread->fresh();
        });
        /*
        return DB::transaction(fn () => QaThread::update([
            'status' => QaThreadStatus::Resolved->value,
        ]));
        */
    }
}