<?php

declare(strict_types=1);

namespace App\Enums;

enum QaThreadStatus: string
{
    case Resolved = 'resolved';
    case Unresolved = 'unresolved';

    public function label(): string
    {
        return match ($this) {
            self::Resolved => '解決済',
            self::Unresolved => '未解決',
        };
    }
}