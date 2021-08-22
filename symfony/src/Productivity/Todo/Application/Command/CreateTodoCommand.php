<?php

declare(strict_types=1);

namespace Productivity\Todo\Application\Command;

use App\Application\Command\Interface\Command;
use DateTimeImmutable;

final class CreateTodoCommand implements Command
{
    public function __construct(
        private string $title,
        private string $user,
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

    public function getUser(): string
    {
        return $this->user;
    }
}
