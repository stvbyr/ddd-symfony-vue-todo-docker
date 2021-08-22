<?php

declare(strict_types=1);

namespace Productivity\Habit\Domain;

interface HabitRepositoryInterface
{
    public function findAll(): array;

    public function findAllByUser(User $user): array;

    public function find(HabitId $todoId): Habit;

    public function remove(HabitId $todoId): void;

    public function save(Habit $todo): void;
}
