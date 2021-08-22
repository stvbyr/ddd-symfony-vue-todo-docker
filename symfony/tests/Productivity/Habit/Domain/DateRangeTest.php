<?php

declare(strict_types=1);

namespace Tests\Productivity\Habit\Domain;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Productivity\Habit\Domain\DateRange;
use Productivity\Habit\Domain\Frequency;

class DateRangeTest extends TestCase
{
    public const DATE_FORMAT = 'Y-m-d';

    public function testCanBeTransformedToArray(): void
    {
        $from = new DateTimeImmutable('today');
        $to = new DateTimeImmutable('+3 days');
        $frequency = new Frequency();

        $dateRange = new DateRange(
            $from,
            $to,
            $frequency
        );

        $this->assertSame([
            'from' => $from->format(self::DATE_FORMAT),
            'to' => $to->format(self::DATE_FORMAT),
            'frequency' => $frequency->toString(),
        ], $dateRange->toArray());
    }
}
