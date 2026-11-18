<?php

declare(strict_types=1);

namespace App\UseCases\Thread;

use App\Models\QaThread;
use Illuminate\Support\Facades\DB;

/**
 * QuestionCategory(出題分野マスタ) の更新ユースケース。
 */
final class DestroyAction
{
    /**
     * @param array{name: string, slug: string, sort_order?: ?int, description?: ?string} $validated QuestionCategory/UpdateRequest::rules() で検証済
     */
    public function __invoke(QaThread $thread): void
    {
        DB::transaction(fn () => $thread->delete());
    }
}