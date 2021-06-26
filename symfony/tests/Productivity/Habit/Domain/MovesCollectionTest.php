<?php

declare(strict_types=1);

namespace Tests\Productivity\Habit\Domain;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Productivity\Habit\Domain\Move;
use Productivity\Habit\Domain\MovesCollection;
use Productivity\Habit\Domain\Status;

class MovesCollectionTest extends TestCase
{
    public const DATE_FORMAT = 'Y-m-d';

    public function testCanBeTransformedToArray(): void
    {
        $today = new DateTimeImmutable('today');
        $tomorrow = new DateTimeImmutable('+1 day');

        $moves = [
            new Move($today),
            new Move($tomorrow),
        ];

        $collection = new MovesCollection($moves);

        $this->assertSame([
            [
                'scheduledDate' => $today->format(self::DATE_FORMAT),
                'status' => Status::OPEN,
            ],
            [
                'scheduledDate' => $tomorrow->format(self::DATE_FORMAT),
                'status' => Status::OPEN,
            ],
        ], $collection->toArray());
    }
}
