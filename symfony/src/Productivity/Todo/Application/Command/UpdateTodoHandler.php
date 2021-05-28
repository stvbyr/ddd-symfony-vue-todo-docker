<?php

declare(strict_types=1);

namespace Productivity\Todo\Application\Command;

use Productivity\Shared\Application\Command\Interface\Command;
use Productivity\Shared\Application\Command\Interface\Handler;
use Productivity\Todo\Domain\Todo;
use Productivity\Todo\Domain\TodoId;
use Productivity\Todo\Domain\TodoRepositoryInterface;

final class UpdateTodoHandler implements Handler
{
    public function __construct(private TodoRepositoryInterface $todoRepository)
    {
    }

    /**
     * @param UpdateTodoCommand $command
     */
    public function __invoke(Command $command): void
    {
        $todoId = TodoId::fromString($command->getId());
        $todo = $this->todoRepository->find($todoId);

        $updatedTodo = Todo::create(
            $todoId,
            $command->getTitle(),
            $todo->getUser(),
            $command->getScheduledDate(),
            $todo->getStatus(),
        );

        $this->todoRepository->save($updatedTodo);
    }
}
