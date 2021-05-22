<?php

declare(strict_types=1);

namespace Productivity\Todo\Application\Command;

use Productivity\Todo\Domain\Todo;
use Productivity\Todo\Domain\TodoId;

final class CreateTodoHandler implements HandlerInterface
{
    /**
     * @var CreateTodoCommand
     */
    public function __invoke(CommandInterface $command): void
    {
        $todo = Todo::create(
            TodoId::generate(),
            $command->getTitle(),
            $command->getScheduledDate(),
            $command->getStatus(),
        );
    }
}
