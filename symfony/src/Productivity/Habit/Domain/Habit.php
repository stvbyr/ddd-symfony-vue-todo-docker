<?php

declare(strict_types=1);

namespace Productivity\Habit\Domain;

use DateTimeImmutable;

class Habit
{
    public function __construct(
        private HabitId $id,
        private string $title,
        private User $user,
        private DateRange $dateRange,
        private ?MovesCollection $moves = null
    ) {
        $this->initMovesIfNotAlreadyDone();
    }

    public static function create(
        HabitId $id,
        string $title,
        User $user,
        DateRange $dateRange,
        ?MovesCollection $moves = null,
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

    public function getMoves(): MovesCollection
    {
        return $this->moves;
    }

    private function initMovesIfNotAlreadyDone(): void
    {
        if (!$this->moves) {
            $this->generateMoves();
        }
    }

    private function generateMoves(): void
    {
        $this->moves = MovesCollection::createFromDateRange($this->dateRange);
    }
}
