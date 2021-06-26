<?php

declare(strict_types=1);

namespace Productivity\Habit\Infrastructure;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use DomainException;
use Productivity\Habit\Domain\DateRange;
use Productivity\Habit\Domain\Frequency;
use Productivity\Habit\Domain\Habit;
use Productivity\Habit\Domain\HabitId;
use Productivity\Habit\Domain\HabitRepositoryInterface;
use Productivity\Habit\Domain\User;

class DbalHabitRepository implements HabitRepositoryInterface
{
    public const DATE_FORMAT = 'Y-m-d';

    public function __construct(private Connection $connection)
    {
    }

    public function findAll(): array
    {
        return $this->connection->prepare('
            SELECT * FROM habit
        ')->executeQuery()->fetchAllAssociative();
    }

    public function findAllByUser(User $user): array
    {
        return $this->connection->prepare('
            SELECT * FROM habit WHERE user = :userId 
        ')->executeQuery(['userId' => $user->getUsername()])->fetchAllAssociative();
    }

    public function find(HabitId $habitId): Habit
    {
        $result = $this->connection->prepare('
            SELECT * FROM habit WHERE uuid = :uuid
        ')->executeQuery(['uuid' => $habitId->toString()])->fetchAssociative();

        if (!$result) {
            throw new DomainException("Habit could not be found for uuid: {$habitId->toString()}");
        }

        return Habit::create(
            $habitId,
            $result['title'],
            new User($result['user']),
            new DateRange(
                DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $result['from']),
                DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $result['to']),
                new Frequency($result['frequency'])
            )
        );
    }

    public function remove(HabitId $habitId): void
    {
        $result = $this->connection->prepare('
            DELETE FROM habit WHERE uuid = :uuid
        ')->executeStatement(['uuid' => $habitId->toString()]);

        if (!$result) {
            throw new DomainException("Habit could not be deleted for uuid: {$habitId->toString()}. Maybe not in database.");
        }
    }

    public function save(Habit $habit): void
    {
        $isInDb = $this->connection->prepare('
            SELECT * FROM habit WHERE uuid = :uuid
        ')->executeStatement(['uuid' => $habit->getId()->toString()]);

        $data = [
            'uuid' => $habit->getId()->toString(),
            'title' => $habit->getTitle(),
            'from' => $habit->getDateRange()->getFrom()->format(self::DATE_FORMAT),
            'to' => $habit->getDateRange()->getFrom()->format(self::DATE_FORMAT),
            'frequency' => $habit->getDateRange()->getFrequency()->toString(),
            'user' => $habit->getUser()->getUsername(),
            'moves' => $habit->getMoves()->toArray(),
        ];

        $result = $isInDb
            ? $this->connection->update('habit', $data, ['uuid' => $habit->getId()->toString()])
            : $this->connection->insert('habit', $data)
        ;

        if (!$result) {
            throw new DomainException("Habit could not be saved for uuid: {$habit->getId()->toString()}.");
        }
    }
}
