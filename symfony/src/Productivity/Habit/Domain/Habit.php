<?php

namespace Productivity\Habit\Domain;

use DateTimeImmutable;

class Habit
{
    private MovesCollection $moves;

    public function __construct(
        private HabitId $id,
        private string $title,
        private User $user,
        private DateRange $dateRange,
    ) {
        $this->generateMoves();
    }

    public static function create(
        HabitId $id,
        string $title,
        User $user,
        DateRange $dateRange,
    ): self {
        $functionArguments = get_defined_vars();

        $arguments = array_merge($functionArguments, []);

        return new self(...$arguments);
    }

    public function countMoves(): int
    {
        return $this->moves->count();
    }

    public function getMoveFromDate(DateTimeImmutable $date): Move
    {
        return $this->moves->findByDate($date);
    }

    public function getId(): HabitId
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getDateRange(): DateRange
    {
        return $this->dateRange;
    }

    public function getFrequency(): Frequency
    {
        return $this->frequency;
    }

    private function generateMoves(): void
    {
        $this->moves = MovesCollection::createFromDateRange($this->dateRange);
    }
}
