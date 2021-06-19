<?php

declare(strict_types=1);

namespace Productivity\Todo\Application\Command;

use App\Application\Command\Interface\Handler;
use Productivity\Todo\Domain\TodoId;
use Productivity\Todo\Domain\TodoRepositoryInterface;

final class DeleteTodoHandler implements Handler
{
    public function __construct(private TodoRepositoryInterface $todoRepository)
    {
    }

    public function __invoke(UpdateTodoCommand $command): void
    {
        $todoId = TodoId::fromString($command->getId());
        $todo = $this->todoRepository->remove($todoId);
    }
}
