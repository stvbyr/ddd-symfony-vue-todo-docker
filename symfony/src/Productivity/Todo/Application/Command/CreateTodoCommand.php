<?php

declare(strict_types=1);

namespace Productivity\Todo\Application\Command;

use DateTimeImmutable;
use Productivity\Shared\Application\Command\Interface\Command;
use Productivity\Todo\Domain\User;

final class CreateTodoCommand implements Command
{
    public function __construct(
        private string $title,
        private User $user,
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

    public function getUser(): User
    {
        return $this->user;
    }
}
