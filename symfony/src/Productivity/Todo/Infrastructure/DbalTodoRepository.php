<?php

declare(strict_types=1);

namespace Productivity\Todo\Infrastructure;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use DomainException;
use Productivity\Todo\Domain\Status;
use Productivity\Todo\Domain\Todo;
use Productivity\Todo\Domain\TodoId;
use Productivity\Todo\Domain\TodoRepositoryInterface;
use Productivity\Todo\Domain\User;

class DbalTodoRepository implements TodoRepositoryInterface
{
    public const DATE_FORMAT = 'Y-m-d';

    public function __construct(private Connection $connection)
    {
    }

    public function findAll(): array
    {
        return $this->connection->prepare('
            SELECT * FROM todo
        ')->executeQuery()->fetchAllAssociative();
    }

    public function findAllByUser(User $user): array
    {
        return $this->connection->prepare('
            SELECT * FROM todo WHERE user = :userId 
        ')->executeQuery(['userId' => $user->getId()])->fetchAllAssociative();
    }

    public function find(TodoId $todoId): Todo
    {
        $result = $this->connection->prepare('
            SELECT * FROM todo WHERE uuid = :uuid
        ')->executeQuery(['uuid' => $todoId->toString()])->fetchAssociative();

        if (!$result) {
            throw new DomainException("Todo could not be found for uuid: {$todoId->toString()}");
        }

        return Todo::create(
            $todoId,
            $result['title'],
            new User($result['user']),
            DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $result['scheduled_date']),
            new Status($result['state'])
        );
    }

    public function remove(TodoId $todoId): void
    {
        $result = $this->connection->prepare('
            DELETE FROM todo WHERE uuid = :uuid
        ')->executeStatement(['uuid' => $todoId->toString()]);

        if (!$result) {
            throw new DomainException("Todo could not be deleted for uuid: {$todoId->toString()}. Maybe not in database.");
        }
    }

    public function save(Todo $todo): void
    {
        $isInDb = $this->connection->prepare('
            SELECT * FROM todo WHERE uuid = :uuid
        ')->executeStatement(['uuid' => $todo->getId()->toString()]);

        $data = [
            'uuid' => $todo->getId()->toString(),
            'title' => $todo->getTitle(),
            'scheduled_date' => $todo->getScheduledDate()->format(self::DATE_FORMAT),
            'user' => $todo->getUser()->getId(),
            'status' => $todo->getStatus()->toString(),
        ];

        $result = $isInDb
            ? $this->connection->update('todo', $data, ['uuid' => $todo->getId()->toString()])
            : $this->connection->insert('todo', $data)
        ;

        if (!$result) {
            throw new DomainException("Todo could not be saved for uuid: {$todo->getId()->toString()}.");
        }
    }
}
