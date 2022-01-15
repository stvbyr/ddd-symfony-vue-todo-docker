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
        $this->assertSame($from, $dateRange->getFrom());
        $this->assertSame($to, $dateRange->getTo());
        $this->assertSame($frequency, $dateRange->getFrequency());
    }

    public function testCanBeCreatedFromArray(): void
    {
        $dateRangeConf = [
            'from' => new DateTimeImmutable('today'),
            'to' => new DateTimeImmutable('+1 days'),
            'frequency' => new Frequency(),
        ];

        $dateRange = DateRange::fromArray($dateRangeConf);

        $this->assertSame([
            'from' => $dateRangeConf['from']->format(self::DATE_FORMAT),
            'to' => $dateRangeConf['to']->format(self::DATE_FORMAT),
            'frequency' => $dateRangeConf['frequency']->toString(),
        ], $dateRange->toArray());
    }

    public function testshouldThrowErrorIfFromArrayReceivesLackingConfig(): void
    {
        try {
            DateRange::fromArray([]);

            $this->fail('Should fail because the array misses from to and frequency config');
        } catch (\Exception $e) {
            $this->assertStringStartsWith("'from', 'to' and 'frequency' have to present in the dateRange.", $e->getMessage());
        }
    }
}
