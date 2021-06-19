<?php

namespace Productivity\Habit\Domain;

use DateTimeImmutable;
use Exception;

class MovesCollection
{
    public function __construct(
        private array $moves = []
    ) {
    }

    public static function createFromDateRange(DateRange $dateRange): self
    {
        $dates = $dateRange->getDaysInRange();
        $moves = [];
        foreach ($dates as $date) {
            array_push($moves, new Move($date));
        }

        return new self($moves);
    }

    public function count(): int
    {
        return count($this->moves);
    }

    /**
     * @throws Exception
     */
    public function findByDate(DateTimeImmutable $date): Move
    {
        $filtered = array_filter(
            $this->moves,
            fn (Move $move) => $move->getScheduledDate()->setTime(0, 0) == $date->setTime(0, 0)
        );

        if (0 === count($filtered)) {
            throw new Exception('Can\'t find move');
        }

        return array_shift($filtered);
    }

    public function toArray(): array
    {
        return $this->moves;
    }
}
