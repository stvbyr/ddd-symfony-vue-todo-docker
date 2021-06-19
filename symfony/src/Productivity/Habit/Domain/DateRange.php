<?php

namespace Productivity\Habit\Domain;

use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use Productivity\Habit\Domain\Exception\WrongRangeException;

class DateRange
{
    public function __construct(
        private DateTimeImmutable $from,
        private DateTimeImmutable $to,
        private Frequency $frequency,
    ) {
        if ($from->setTime(0, 0) > $to->setTime(0, 0)) {
            throw new WrongRangeException('The from date can\'t be bigger than the to date.');
        }

        if ($from->setTime(0, 0) == $to->setTime(0, 0)) {
            throw new WrongRangeException('From date and to date are not allowed to be the same.');
        }
    }

    public function getDaysInRange(): array
    {
        $period = new DatePeriod(
            $this->from->setTime(0, 0),
            new DateInterval($this->frequency->getIntervalString()),
            $this->to->setTime(0, 0)->modify('+1 day') // mofidy is used to include the last day as well
        );

        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date;
        }

        return $dates;
    }
}
