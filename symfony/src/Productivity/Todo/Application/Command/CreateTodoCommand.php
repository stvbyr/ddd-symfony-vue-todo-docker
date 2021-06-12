<?php

declare(strict_types=1);

namespace Productivity\Todo\Application\Command;

use DateTimeImmutable;
use App\Application\Command\Interface\Command;

final class CreateTodoCommand implements Command
{
    public function __construct(
        private string $title,
        private int $user,
        private DateTimeImmutable $scheduledDate
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getScheduledDate(): DateTimeImmutable
    {
        return $this->scheduledDate;
    }

    public function getUser(): int
    {
        return $this->user;
    }
}
