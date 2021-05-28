<?php

declare(strict_types=1);

namespace Productivity\Todo\Domain;

interface TodoRepositoryInterface
{
    public function findAll(): array;

    public function findAllByUser(User $user): array;

    public function find(TodoId $todoId): Todo;

    public function remove(TodoId $todoId): void;

    public function save(Todo $todo): void;
}
