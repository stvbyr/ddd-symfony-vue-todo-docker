<?php

declare(strict_types=1);

namespace Productivity\Todo\Application\Command;

use DateTimeImmutable;

final class CreateTodoCommand implements CommandInterface
{
    public function __construct(
        private string $title,
        private DateTimeImmutable $scheduledDate,
        private string $status
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

    public function getStatus(): string
    {
        return $this->status;
    }
}
