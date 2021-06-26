<?php

namespace Tests\Productivity\Habit\Domain;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Productivity\Habit\Domain\Exception\MoveIsNotDueException;
use Productivity\Habit\Domain\Move;
use Productivity\Habit\Domain\Status;

class MoveTest extends TestCase
{
    public const DATE_FORMAT = 'Y-m-d';

    public function testMoveIsCreatedWithStatusOpenByDefault()
    {
        $move = new Move(new DateTimeImmutable('today'));

        $this->assertEquals(new Status(Status::OPEN), $move->getStatus());
    }

    public function testCannotBeMarkedAsDoneIfInTheFuture()
    {
        $this->expectException(MoveIsNotDueException::class);

        $move = new Move(new DateTimeImmutable('+1 day'));

        $move->markAsDone();
    }

    public function testCanBeTransformedToArray()
    {
        $date = new DateTimeImmutable('today');

        $move = new Move($date);

        $this->assertSame([
            'scheduledDate' => $date->format(self::DATE_FORMAT),
            'status' => Status::OPEN,
        ], $move->toArray());
    }
}
