<?php

declare(strict_types=1);

namespace Productivity\Todo\Application\Command;

use Productivity\Shared\Application\Command\Interface\Command;
use Productivity\Shared\Application\Command\Interface\Handler;
use Productivity\Todo\Domain\Todo;
use Productivity\Todo\Domain\TodoId;
use Productivity\Todo\Domain\TodoRepositoryInterface;

final class CreateTodoHandler implements Handler
{
    public function __construct(private TodoRepositoryInterface $todoRepository)
    {
    }

    /**
     * @param CreateTodoCommand $command
     */
    public function __invoke(Command $command): void
    {
        $todo = Todo::create(
            TodoId::generate(),
            $command->getTitle(),
            $command->getScheduledDate()
        );

        $this->todoRepository->save($todo);
    }
}
