<?php

namespace Tests\Productivity\Todo\Domain;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Productivity\Todo\Domain\Exception\NoTitleProvidedException;
use Productivity\Todo\Domain\Exception\ScheduledDateIsInThePastException;
use Productivity\Todo\Domain\Exception\TodoIsNotDueException;
use Productivity\Todo\Domain\Status;
use Productivity\Todo\Domain\Todo;
use Productivity\Todo\Domain\TodoId;

class TodoTest extends TestCase
{
    public function testTodoCanBeCreated(): void
    {
        $id = TodoId::generate();
        $title = 'awesome title äöü';
        $scheduledDate = new DateTimeImmutable();
        $status = new Status(Status::OPEN);

        $todo = Todo::create(
            $id,
            $title,
            $scheduledDate,
            $status,
        );

        $this->assertSame($id, $todo->getId());
        $this->assertSame($title, $todo->getTitle());
        $this->assertEquals($scheduledDate->setTime(0, 0, 0), $todo->getScheduledDate());
        $this->assertSame($status, $todo->getStatus());
    }

    public function testTodoMustNotBeEmpty(): void
    {
        $this->expectException(NoTitleProvidedException::class);

        Todo::create(TodoId::generate(), '');
    }

    public function testTodoCannotBeCreatedInThePast(): void
    {
        $this->expectException(ScheduledDateIsInThePastException::class);

        Todo::create(
            TodoId::generate(),
            'awesome',
            (new DateTimeImmutable())->modify('-1 day')
        );
    }

    public function testTodoHasDefaultStatusOfOpen(): void
    {
        $todo = Todo::create(TodoId::generate(), 'awesome');

        $this->assertEquals(new Status(Status::OPEN), $todo->getStatus());
    }

    public function testTodoCanBeMarkedAsDone(): void
    {
        $todo = Todo::create(TodoId::generate(), 'awesome');

        $todo->markAsDone();

        $this->assertEquals(new Status(Status::DONE), $todo->getStatus());
    }

    public function testTodoThatIsNotDueCannotChangeStatus(): void
    {
        $this->expectException(TodoIsNotDueException::class);

        $todo = Todo::create(
            TodoId::generate(),
            'awesome',
            (new DateTimeImmutable())->modify('+1 week')
        );

        $todo->markAsDone();
    }
}
