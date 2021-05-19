<?php

declare(strict_types=1);

namespace TodoManagement\SingularTodos\Application\Command;

use TodoManagement\SingularTodos\Domain\TodoItem;
use TodoManagement\SingularTodos\Domain\TodoItemId;

final class CreateTodoItemHandler implements HandlerInterface
{
    /**
     * @var CreateTodoItemCommand
     */
    public function __invoke(CommandInterface $command): void
    {
        $todoItem = TodoItem::create(
            TodoItemId::generate(),
            $command->getTitle(),
            $command->getScheduledDate(),
            $command->getStatus(),
        );
    }
}
