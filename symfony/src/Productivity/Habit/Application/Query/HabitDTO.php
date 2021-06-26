<?php

namespace Productivity\Habit\Application\Query;

class HabitDTO
{
    public function __construct(
        private string $title,
        private string $user,
        private array $dateRange,
        private array $moves,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getDateRange(): array
    {
        return $this->dateRange;
    }

    public function getMoves(): array
    {
        return $this->moves;
    }
}
