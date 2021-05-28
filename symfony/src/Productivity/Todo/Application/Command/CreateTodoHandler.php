<?php

declare(strict_types=1);

namespace Productivity\Todo\Application\Command;

use Productivity\Shared\Application\Command\Interface\Handler;
use Productivity\Todo\Domain\Todo;
use Productivity\Todo\Domain\TodoId;
use Productivity\Todo\Domain\TodoRepositoryInterface;
use Productivity\Todo\Domain\User;

final class CreateTodoHandler implements Handler
{
    public function __construct(private TodoRepositoryInterface $todoRepository)
    {
    }

    public function __invoke(CreateTodoCommand $command): void
    {
        $todo = Todo::create(
            TodoId::generate(),
            $command->getTitle(),
            new User($command->getUser()),
            $command->getScheduledDate()
        );

        $this->todoRepository->save($todo);
    }
}
