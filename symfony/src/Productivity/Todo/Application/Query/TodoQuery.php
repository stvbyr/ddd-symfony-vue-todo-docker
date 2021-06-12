<?php

declare(strict_types=1);

namespace Productivity\Todo\Application\Query;

use App\Application\Query\Interface\Query;
use Productivity\Todo\Domain\Todo;
use Productivity\Todo\Domain\TodoId;
use Productivity\Todo\Domain\TodoRepositoryInterface;
use Productivity\Todo\Domain\User;

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

    public function findAllByUser(User $user): array
    {
        $todos = $this->todoRepository->findAllByUser($user);

        return $this->mapTodosToDTOs($todos);
    }

    public function find(TodoId $todoId): array
    {
        $todo = $this->todoRepository->find($todoId);

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
            $todo->getStatus()->toString(),
        ), $todos);
    }
}
