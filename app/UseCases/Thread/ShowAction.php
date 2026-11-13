<?php

declare(strict_types=1);

namespace App\UseCases\Thread;

use App\Models\QaThread;

final class ShowAction
{
    public function __invoke(QaThread $thread): Thread
    {
        return $thread->with('replies', 'user', 'certification')
                      ->withCount('replies')
                      ->get();
    }
}