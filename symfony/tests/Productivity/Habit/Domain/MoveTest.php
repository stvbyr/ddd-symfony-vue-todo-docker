<?php

declare(strict_types=1);

namespace Tests\Productivity\Habit\Domain;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Productivity\Habit\Domain\Exception\MoveIsNotDueException;
use Productivity\Habit\Domain\Move;
use Productivity\Habit\Domain\Status;

class MoveTest extends TestCase
{
    public const DATE_FORMAT = 'Y-m-d';

    public function testMoveIsCreatedWithStatusOpenByDefault(): void
    {
        $move = new Move(new DateTimeImmutable('today'));

        $this->assertEquals(new Status(Status::OPEN), $move->getStatus());
    }

    public function testCannotBeMarkedAsDoneIfInTheFuture(): void
    {
        $this->expectException(MoveIsNotDueException::class);

        $move = new Move(new DateTimeImmutable('+1 day'));

        $move->markAsDone();
    }

    public function testCanBeTransformedToArray(): void
    {
        $date = new DateTimeImmutable('today');

        $move = new Move($date);

        $this->assertSame([
            'scheduledDate' => $date->format(self::DATE_FORMAT),
            'status' => Status::OPEN,
        ], $move->toArray());
    }
}
