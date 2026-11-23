<?php

declare(strict_types=1);

namespace App\Exceptions\Plan;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Exception;

class PlanNotDeletableException extends Exception
{
    public function __construct(
        string $message = '公開中状態のプランは削除できません。',
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 409, $previous);
    }
}
