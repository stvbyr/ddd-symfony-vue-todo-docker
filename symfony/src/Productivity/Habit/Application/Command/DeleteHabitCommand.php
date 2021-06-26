<?php

declare(strict_types=1);

namespace Productivity\Todo\Application\Command;

use App\Application\Command\Interface\Command;

final class DeleteTodoCommand implements Command
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
