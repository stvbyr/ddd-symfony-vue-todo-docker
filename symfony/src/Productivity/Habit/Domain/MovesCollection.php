<?php

namespace Productivity\Habit\Domain;

use DateTimeImmutable;
use Exception;
use JsonSerializable;

class MovesCollection implements JsonSerializable
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
        $isSameDate = fn (Move $move) => $move->getScheduledDate()->setTime(0, 0) == $date->setTime(0, 0);

        $filtered = array_filter(
            $this->moves,
            $isSameDate
        );

        if (0 === count($filtered)) {
            throw new Exception('Can\'t find move');
        }

        return array_shift($filtered);
    }

    public function jsonSerialize(): array
    {
        return $this->moves;
    }

    public function toArray(): array
    {
        return json_decode(json_encode($this), true);
    }
}
