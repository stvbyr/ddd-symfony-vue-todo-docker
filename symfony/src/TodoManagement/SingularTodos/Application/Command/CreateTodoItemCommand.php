<?php

declare(strict_types=1);

namespace TodoManagement\SingularTodos\Application\Command;

use DateTimeImmutable;

final class CreateTodoItemCommand implements CommandInterface
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
