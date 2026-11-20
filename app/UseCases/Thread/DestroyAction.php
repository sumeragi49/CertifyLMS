<?php

declare(strict_types=1);

namespace App\UseCases\Thread;

use App\Models\QaThread;
use Illuminate\Support\Facades\DB;

final class DestroyAction
{
    public function __invoke(QaThread $thread): void
    {
        DB::transaction(fn () => $thread->delete());
    }
}