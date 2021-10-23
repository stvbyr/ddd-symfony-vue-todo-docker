<?php

declare(strict_types=1);

namespace Productivity\Habit\Application\Command;

use App\Application\Command\Interface\Command;

final class UpdateHabitCommand implements Command
{
    public function __construct(
        private string $id,
        private string $title,
        private string $user,
        private array $dateRange
    ) {
    }

    public function getId(): string
    {
        return $this->id;
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
}
