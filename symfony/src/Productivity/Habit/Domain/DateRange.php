<?php

namespace Productivity\Habit\Domain;

use DateTimeImmutable;
use Productivity\Habit\Domain\Exception\WrongRangeException;

class DateRange
{
    private function __construct(private DateTimeImmutable $from, private DateTimeImmutable $to)
    {
        if ($from->setTime(0, 0, 0) > $to->setTime(0, 0, 0)) {
            throw new WrongRangeException('The from date can\'t be bigger than the to date.');
        }

        if ($from->setTime(0, 0, 0) === $to->setTime(0, 0, 0)) {
            throw new WrongRangeException('From date and to date are not allowed to be the same.');
        }
    }
}
