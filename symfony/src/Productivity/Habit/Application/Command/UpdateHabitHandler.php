<?php

declare(strict_types=1);

namespace Productivity\Habit\Application\Command;

use App\Application\Command\Interface\Handler;
use Productivity\Habit\Domain\DateRange;
use Productivity\Habit\Domain\Habit;
use Productivity\Habit\Domain\HabitId;
use Productivity\Habit\Domain\HabitRepositoryInterface;
use Productivity\Habit\Domain\User;

final class UpdateHabitHandler implements Handler
{
    public function __construct(private HabitRepositoryInterface $habitRepository)
    {
    }

    public function __invoke(UpdateHabitCommand $command): void
    {
        $habitId = HabitId::fromString($command->getId());
        $habit = $this->habitRepository->find($habitId);

        $updatedHabit = new Habit(
            $habit->getId(),
            $command->getTitle(),
            new User($command->getUser()),
            DateRange::fromArray($command->getDateRange()),
        );

        $this->habitRepository->save($updatedHabit);
    }
}
