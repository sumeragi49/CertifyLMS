<?php

declare(strict_types=1);

namespace App\Exceptions\MeetingPack;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Exception;

class MeetingPackInvalidTransitionException extends Exception
{
    public static function forPublish(): self
    {
        return new self('下書き状態の資格のみ公開できます。');
    }

    public static function forUnpublish(): self
    {
        return new self('公開中の資格のみ下書き状態に戻せます。');
    }

    public static function forArchive(): self
    {
        return new self('公開中の資格のみアーカイブできます。');
    }

    private function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }
}
