<?php

declare(strict_types=1);

namespace App\UseCases\ThreadReply;

use App\Enums\QaThreadStatus;
use App\Models\QaThread;
use App\Models\QaReply;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

final class StoreAction
{
    public function __invoke(QaThread $thread, array $validated): QaReply
    {
        return DB::transaction(function () use ($thread, $validated) {
            $reply = QaReply::create([
                'user_id' => auth()->user()->id,
                'qa_thread_id' => $thread->id,
                'body' => $validated['body'],
            ]);

            return $reply;
        });
    }
}