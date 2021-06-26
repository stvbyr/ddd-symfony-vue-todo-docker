<?php

namespace Productivity\Habit\Domain;

use DateTimeImmutable;
use JsonSerializable;
use Productivity\Habit\Domain\Exception\MoveIsNotDueException;

final class Move implements JsonSerializable
{
    private Status $status;

    public function __construct(
        private DateTimeImmutable $scheduledDate,
    ) {
        $this->status = new Status(Status::OPEN);
    }

    /**
     * @throws MoveIsNotDueException
     */
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

    public function jsonSerialize(): array
    {
        return [
            'scheduledDate' => $this->scheduledDate->format('Y-m-d'),
            'status' => $this->status->toString(),
        ];
    }

    public function toArray()
    {
        return json_decode(json_encode($this), true);
    }
}
