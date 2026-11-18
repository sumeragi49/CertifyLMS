<?php

declare(strict_types=1);

namespace App\UseCases\Thread;

use App\Enums\QaThreadStatus;
use App\Models\QaThread;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

final class StoreAction
{
    public function __invoke(User $student, array $validated): QaThread
    {
        return DB::transaction(fn () => QaThread::create([
            'user_id' => $student->id,
            'certification_id' => $validated['certification_id'],
            'title' => $validated['title'],
            'body' => $validated['body'],
            'status' => QaThreadStatus::Unresolved->value,
        ]));
    }
}