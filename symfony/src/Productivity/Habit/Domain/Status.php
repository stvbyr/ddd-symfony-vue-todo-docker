<?php

declare(strict_types=1);

namespace Productivity\Habit\Domain;

final class Status
{
    public const OPEN = 'open';
    public const DONE = 'done';

    public function __construct(
        private string $state = Status::OPEN,
    ) {
    }

    public function toString(): string
    {
        return $this->state;
    }
}
