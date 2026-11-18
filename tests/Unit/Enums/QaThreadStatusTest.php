<?php

namespace Tests\Unit\Enums;

use App\Enums\QaThreadStatus;
use PHPUnit\Framework\TestCase;

class QaThreadStatusTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_enum_lists_resolved_or_unresolved_values(): void
    {
        $values = array_map(fn (QaThreadStatus $s) => $s->value, QaThreadStatus::cases());

        $this->assertEqualsCanonicalizing(
            ['resolved','unresolved'],
            $values,
        );
    }

    public function test_japanese_labels(): void
    {
        $this->assertSame('解決済', QaThreadStatus::Resolved->label());
        $this->assertSame('未解決', QaThreadStatus::Unresolved->label());
    }
}
