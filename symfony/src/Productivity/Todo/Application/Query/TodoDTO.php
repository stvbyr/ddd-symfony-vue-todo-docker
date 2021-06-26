<?php

declare(strict_types=1);

namespace Productivity\Todo\Application\Query;

use DateTimeImmutable;

class TodoDTO
{
    public function __construct(
        private string $title,
        private DateTimeImmutable $scheduledDate,
        private string $status,
        private string $user,
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

    public function getUser(): string
    {
        return $this->user;
    }
}
