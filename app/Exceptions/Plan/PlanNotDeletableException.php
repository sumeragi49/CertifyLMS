<?php

declare(strict_types=1);

namespace App\Exceptions\Plan;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Exception;

class PlanNotDeletableException extends Exception
{
    public function __construct(
        string $message = '削除できるのは下書き状態かつ受講者数が0のプランです',
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 409, $previous);
    }
}
