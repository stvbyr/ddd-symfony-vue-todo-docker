<?php

declare(strict_types=1);

namespace Productivity\Todo\Application\Command;

use App\Application\Command\Interface\Handler;
use Productivity\Habit\Domain\DateRange;
use Productivity\Habit\Domain\Habit;
use Productivity\Habit\Domain\HabitId;
use Productivity\Habit\Domain\HabitRepositoryInterface;
use Productivity\Habit\Domain\User;

final class CreateHabitHandler implements Handler
{
    public function __construct(private HabitRepositoryInterface $habitRepository)
    {
    }

    public function __invoke(CreateHabitCommand $command): void
    {
        $habit = new Habit(
            HabitId::generate(),
            $command->getTitle(),
            new User($command->getUser()),
            DateRange::fromArray($command->getDateRange()),
        );

        $this->habitRepository->save($habit);
    }
}
