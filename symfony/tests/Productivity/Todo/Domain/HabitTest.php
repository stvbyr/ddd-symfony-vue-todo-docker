<?php

declare(strict_types=1);

namespace Tests\Productivity\Todo\Domain;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class TodoTest extends TestCase
{
    public function testHabitCanBeCreated(): void
    {
        $id = HabitId::generate();
        $title = 'Eat 2000 calories every day';
        $user = new User(1);
        $dateRange = new DateRange(new DateTimeImmutable(), (new DateTimeImmutable())->modify('+1 weeks'));
        $frequency = new Frequency(Frequency::DAILY);

        $habit = Habit::create(
            $id,
            $title,
            $user,
            $dateRange,
            $frequency,
        );

        $this->assertSame($id, $habit->getId());
        $this->assertSame($title, $habit->getTitle());
        $this->assertSame($user, $habit->getUser());
        $this->assertSame(7, $habit->countMoves());
    }
}
