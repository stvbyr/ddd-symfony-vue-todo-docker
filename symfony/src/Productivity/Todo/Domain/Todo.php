<?php

declare(strict_types=1);

namespace Productivity\Todo\Domain;

use DateTimeImmutable;
use Productivity\Todo\Domain\Exception\NoTitleProvidedException;
use Productivity\Todo\Domain\Exception\ScheduledDateIsInThePastException;
use Productivity\Todo\Domain\Exception\TodoIsNotDueException;

final class Todo
{
    private function __construct(
        private TodoId $id,
        private string $title,
        private ?DateTimeImmutable $scheduledDate,
        private Status $status,
        private User $user
    ) {
    }

    public static function create(
        TodoId $id,
        string $title,
        User $user,
        ?DateTimeImmutable $scheduledDate = null,
        ?Status $status = null
    ): self {
        $functionArguments = get_defined_vars();

        if ('' === $title) {
            throw new NoTitleProvidedException('There was no title provided for the todo item.');
        }

        if ($scheduledDate && (new DateTimeImmutable())->setTime(0, 0, 0) > $scheduledDate->setTime(0, 0, 0)) {
            throw new ScheduledDateIsInThePastException('You can\'t create todo items that happen in the past.');
        }

        $arguments = array_merge($functionArguments, [
            'scheduledDate' => $scheduledDate ? $scheduledDate->setTime(0, 0, 0) : null,
            'status' => $status ?? new Status(Status::OPEN),
        ]);

        return new self(...$arguments);
    }

    public function markAsDone(): void
    {
        if ($this->scheduledDate > (new DateTimeImmutable())->setTime(0, 0, 0)) {
            throw new TodoIsNotDueException('The todo item is in the future. You can\'t mark it done yet.');
        }

        $this->status = new Status(Status::DONE);
    }

    public function getId(): TodoId
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getScheduledDate(): DateTimeImmutable
    {
        return $this->scheduledDate;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
