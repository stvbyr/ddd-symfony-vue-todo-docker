<?php

declare(strict_types=1);

namespace Productivity\Todo\Infrastructure;

use Productivity\Todo\Domain\Todo;
use Productivity\Todo\Domain\TodoId;
use Productivity\Todo\Domain\TodoRepositoryInterface;

class DbalTodoRepository implements TodoRepositoryInterface
{
    public function findAll(): array
    {
    }

    public function findAllDone(): array
    {
    }

    public function findAllOpen(): array
    {
    }

    public function findTodo(TodoId $todoId): Todo
    {
    }

    public function remove(TodoId $todoId): void
    {
    }

    public function save(Todo $todo): void
    {
    }
}
