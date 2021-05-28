<?php

declare(strict_types=1);

namespace Productivity\Todo\Application\Query;

use Productivity\Shared\Application\Query\Interface\Query;
use Productivity\Todo\Domain\Todo;
use Productivity\Todo\Domain\TodoId;
use Productivity\Todo\Domain\TodoRepositoryInterface;

class TodoQuery implements Query
{
    public function __construct(private TodoRepositoryInterface $todoRepository)
    {
    }

    public function findAll(): array
    {
        $todos = $this->todoRepository->findAll();

        return $this->mapTodosToDTOs($todos);
    }

    public function findAllDone(): array
    {
        $todos = $this->todoRepository->findAllDone();

        return $this->mapTodosToDTOs($todos);
    }

    public function findAllOpen(): array
    {
        $todos = $this->todoRepository->findAllOpen();

        return $this->mapTodosToDTOs($todos);
    }

    public function findTodo(TodoId $todoId): array
    {
        $todo = $this->todoRepository->findTodo($todoId);

        return $this->mapTodoToDTO($todo);
    }

    private function mapTodoToDTO(Todo $todo)
    {
        return $this->mapTodosToDTOs([$todo]);
    }

    private function mapTodosToDTOs(array $todos)
    {
        return array_map(fn (Todo $todo) => new TodoDTO(
            $todo->getTitle(),
            $todo->getScheduledDate(),
            $todo->getStatus()->asString()
        ), $todos);
    }
}
