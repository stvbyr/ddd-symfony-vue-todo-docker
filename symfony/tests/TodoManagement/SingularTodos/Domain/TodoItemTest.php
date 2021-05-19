<?php

namespace Test\TodoManagement\SingularTodos\Domain;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use TodoManagement\SingularTodos\Domain\Exception\NoTitleProvidedException;
use TodoManagement\SingularTodos\Domain\Exception\ScheduledDateIsInThePastException;
use TodoManagement\SingularTodos\Domain\Exception\TodoItemIsNotDueException;
use TodoManagement\SingularTodos\Domain\Status;
use TodoManagement\SingularTodos\Domain\TodoItem;
use TodoManagement\SingularTodos\Domain\TodoItemId;

class TodoItemTest extends TestCase
{
    public function testTodoItemCanBeCreated(): void
    {
        $id = TodoItemId::generate();
        $title = 'awesome title äöü';
        $scheduledDate = new DateTimeImmutable();
        $status = new Status(Status::OPEN);

        $todoItem = TodoItem::create(
            $id,
            $title,
            $scheduledDate,
            $status,
        );

        $this->assertSame($id, $todoItem->getId());
        $this->assertSame($title, $todoItem->getTitle());
        $this->assertEquals($scheduledDate->setTime(0, 0, 0), $todoItem->getScheduledDate());
        $this->assertSame($status, $todoItem->getStatus());
    }

    public function testTodoItemMustNotBeEmpty(): void
    {
        $this->expectException(NoTitleProvidedException::class);

        TodoItem::create(TodoItemId::generate(), '');
    }

    public function testTodoItemCannotBeCreatedInThePast(): void
    {
        $this->expectException(ScheduledDateIsInThePastException::class);

        TodoItem::create(
            TodoItemId::generate(),
            'awesome',
            (new DateTimeImmutable())->modify('-1 day')
        );
    }

    public function testTodoItemHasDefaultStatusOfOpen(): void
    {
        $todoItem = TodoItem::create(TodoItemId::generate(), 'awesome');

        $this->assertEquals(new Status(Status::OPEN), $todoItem->getStatus());
    }

    public function testTodoItemCanBeMarkedAsDone(): void
    {
        $todoItem = TodoItem::create(TodoItemId::generate(), 'awesome');

        $todoItem->markAsDone();

        $this->assertEquals(new Status(Status::DONE), $todoItem->getStatus());
    }

    public function testTodoItemThatIsNotDueCannotChangeStatus(): void
    {
        $this->expectException(TodoItemIsNotDueException::class);

        $todoItem = TodoItem::create(
            TodoItemId::generate(),
            'awesome',
            (new DateTimeImmutable())->modify('+1 week')
        );

        $todoItem->markAsDone();
    }
}
