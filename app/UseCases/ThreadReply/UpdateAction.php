<?php

declare(strict_types=1);

namespace App\UseCases\ThreadReply;

use App\Models\QaReply;
use Illuminate\Support\Facades\DB;

final class UpdateAction
{
    public function __invoke(QaReply $reply, array $validated): QaReply
    {
        return DB::transaction(function () use ($reply, $validated) {
            $reply->update($validated);

            return $reply->fresh();
        });
    }
}