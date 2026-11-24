<?php

declare(strict_types=1);

namespace App\Exceptions\MeetingPack;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Exception;

class MeetingPackNotDeletableException extends Exception
{
    public function __construct(
        string $message = '公開中状態の面談パックは削除できません。',
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 409, $previous);
    }
}
