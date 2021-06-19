<?php

namespace Productivity\Habit\Domain;

use DateTimeImmutable;
use Productivity\Habit\Domain\Exception\MoveIsNotDueException;

final class Move
{
    private Status $status;

    public function __construct(
        private DateTimeImmutable $scheduledDate,
    ) {
        $this->status = new Status(Status::OPEN);
    }

    public function markAsDone(): void
    {
        if ($this->scheduledDate > (new DateTimeImmutable())->setTime(0, 0)) {
            throw new MoveIsNotDueException('The move is in the future. You can\'t mark it done yet.');
        }

        $this->status = new Status(Status::DONE);
    }

    public function getScheduledDate(): DateTimeImmutable
    {
        return $this->scheduledDate;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}
