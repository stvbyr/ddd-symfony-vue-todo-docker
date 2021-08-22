<?php

declare(strict_types=1);

namespace Productivity\Todo\Application\Command;

use App\Application\Command\Interface\Command;

final class CreateHabitCommand implements Command
{
    public function __construct(
        private string $title,
        private string $user,
        private array $dateRange
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
}
