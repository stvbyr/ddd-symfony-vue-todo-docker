<?php

declare(strict_types=1);

namespace Productivity\Todo\Application\Command;

use DateTimeImmutable;
use Productivity\Todo\Application\Command\Interface\Command;

final class EditTodoCommand implements Command
{
    public function __construct(
        private string $id,
        private string $title,
        private DateTimeImmutable $scheduledDate,
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

    public function getScheduledDate(): DateTimeImmutable
    {
        return $this->scheduledDate;
    }
}