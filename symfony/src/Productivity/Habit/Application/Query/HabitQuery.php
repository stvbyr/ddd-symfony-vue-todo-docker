<?php

declare(strict_types=1);

namespace Productivity\Habit\Application\Query;

use Productivity\Habit\Domain\Habit;
use Productivity\Habit\Domain\HabitId;
use Productivity\Habit\Domain\HabitRepositoryInterface;
use Productivity\Habit\Domain\User;

class HabitQuery
{
    public function __construct(private HabitRepositoryInterface $habitRepository)
    {
    }

    public function findAll(): array
    {
        $habits = $this->habitRepository->findAll();

        return $this->mapHabitsToDTOs($habits);
    }

    public function findAllByUser(string $username): array
    {
        $habits = $this->habitRepository->findAllByUser(new User($username));

        return $this->mapHabitsToDTOs($habits);
    }

    public function find(string $habitId): array
    {
        $habit = $this->habitRepository->find(HabitId::fromString($habitId));

        return $this->maphabitToDTO($habit);
    }

    private function maphabitToDTO(Habit $habit): array
    {
        return $this->mapHabitsToDTOs([$habit]);
    }

    private function mapHabitsToDTOs(array $habits): array
    {
        return array_map(fn (Habit $habit) => new HabitDTO(
            $habit->getTitle(),
            $habit->getUser()->getUsername(),
            $habit->getDateRange()->toArray(),
            $habit->getMoves()->toArray(),
        ), $habits);
    }
}
