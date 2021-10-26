<?php

declare(strict_types=1);

namespace Productivity\Habit\Application\Command;

use App\Application\Command\Interface\Command;

final class DeleteHabitCommand implements Command
{
    public function __construct(
        private string $id,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }
}
