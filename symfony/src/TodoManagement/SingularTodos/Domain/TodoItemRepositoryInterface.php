<?php

declare(strict_types=1);

namespace TodoManagement\SingularTodos\Domain;

interface TodoItemRepositoryInterface
{
    public function getAll(): array;

    public function getAllDone(): array;

    public function getAllOpen(): array;

    public function remove(TodoItemId $todoItemId): void;

    public function save(TodoItem $todoItem): void;
}
