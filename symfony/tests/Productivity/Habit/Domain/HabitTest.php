<?php

declare(strict_types=1);

namespace Tests\Productivity\Habit\Domain;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Productivity\Habit\Domain\DateRange;
use Productivity\Habit\Domain\Exception\MoveIsNotDueException;
use Productivity\Habit\Domain\Exception\WrongRangeException;
use Productivity\Habit\Domain\Frequency;
use Productivity\Habit\Domain\Habit;
use Productivity\Habit\Domain\HabitId;
use Productivity\Habit\Domain\Status;
use Productivity\Habit\Domain\User;

class HabitTest extends TestCase
{
    private array $habitConfig;

    public function setUp(): void
    {
        $this->habitConfig = [
            'id' => HabitId::generate(),
            'title' => 'Eat 2000 calories every day',
            'user' => new User('sentou'),
            'dateRange' => new DateRange(
                new DateTimeImmutable('today'),
                (new DateTimeImmutable('today'))->modify('+1 weeks'),
                new Frequency(Frequency::DAILY),
            ),
        ];
    }

    public function testHabitCanBeCreated(): void
    {
        $habit = Habit::create(...$this->habitConfig);

        $this->assertSame($this->habitConfig['id'], $habit->getId());
        $this->assertSame($this->habitConfig['title'], $habit->getTitle());
        $this->assertSame($this->habitConfig['user'], $habit->getUser());
    }

    public function testCorrectAmountOfMovesAreGenerated()
    {
        $habit = Habit::create(...$this->habitConfig);

        $habit2 = Habit::create(...array_merge($this->habitConfig, [
            'dateRange' => new DateRange(
                new DateTimeImmutable('today'),
                (new DateTimeImmutable('today'))->modify('+3 months'),
                new Frequency(Frequency::MONTHLY),
            ),
        ]));

        $habit3 = Habit::create(...array_merge($this->habitConfig, [
            'dateRange' => new DateRange(
                new DateTimeImmutable('today'), (
                new DateTimeImmutable('today'))->modify('+4 weeks'),
                new Frequency(Frequency::WEEKLY),
            ),
        ]));

        $this->assertSame(8, $habit->countMoves());
        $this->assertSame(4, $habit2->countMoves());
        $this->assertSame(5, $habit3->countMoves());
    }

    public function testSingleMovesCanBeMarkedAsDone(): void
    {
        $habit = Habit::create(...$this->habitConfig);
        $move = $habit->getMoveFromDate(new DateTimeImmutable('today'));

        $this->assertEquals(new Status(Status::OPEN), $move->getStatus());

        $move->markAsDone();

        $this->assertEquals(new Status(Status::DONE), $move->getStatus());
    }

    public function testMoveThatIsNotDueCannotBeMarkedAsDone(): void
    {
        $this->expectException(MoveIsNotDueException::class);

        $habit = Habit::create(...$this->habitConfig);
        $move = $habit->getMoveFromDate((new DateTimeImmutable('today'))->modify('+2 days'));
        $move->markAsDone();
    }

    public function testHabitCantBeCreatedWithEqualDateTimes(): void
    {
        $this->expectException(WrongRangeException::class);

        Habit::create(...array_merge($this->habitConfig, [
            'dateRange' => new DateRange(
                new DateTimeImmutable('+1 days'),
                new DateTimeImmutable('-1 days'),
                new Frequency(Frequency::WEEKLY),
            ),
        ]));
    }

    public function testHabitCantBeCreatedWithReversedDateTimes(): void
    {
        $this->expectException(WrongRangeException::class);

        Habit::create(...array_merge($this->habitConfig, [
            'dateRange' => new DateRange(
                new DateTimeImmutable('today'),
                new DateTimeImmutable('today'),
                new Frequency(Frequency::WEEKLY),
            ),
        ]));
    }
}
