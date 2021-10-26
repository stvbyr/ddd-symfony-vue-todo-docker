<?php

declare(strict_types=1);

namespace Productivity\Habit\Application\Command;

use App\Application\Command\Interface\Handler;
use Productivity\Habit\Domain\HabitId;
use Productivity\Habit\Domain\HabitRepositoryInterface;

final class DeleteHabitHandler implements Handler
{
    public function __construct(private HabitRepositoryInterface $habitRepository)
    {
    }

    public function __invoke(DeleteHabitCommand $command): void
    {
        $habitId = HabitId::fromString($command->getId());
        $this->habitRepository->remove($habitId);
    }
}
