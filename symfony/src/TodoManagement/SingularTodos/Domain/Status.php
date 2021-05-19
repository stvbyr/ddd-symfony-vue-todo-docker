<?php

declare(strict_types=1);

namespace TodoManagement\SingularTodos\Domain;

final class Status
{
    const OPEN = 'open';
    const DONE = 'done';

    public function __construct(
        private string $state = Status::OPEN,
    ) {
    }

    public function asString(): string
    {
        return $this->state;
    }
}
