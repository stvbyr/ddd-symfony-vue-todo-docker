<?php

declare(strict_types=1);

namespace Tests\Productivity\Todo\Application;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Productivity\Todo\Application\Command\UpdateTodoCommand;
use Productivity\Todo\Application\Command\UpdateTodoHandler;
use Productivity\Todo\Domain\TodoRepositoryInterface;

final class UpdateTodoCommandHandlerTest extends TestCase
{
    public function testTodoItemIsSaved(): void
    {
        $command = new UpdateTodoCommand('cd28a79b-511d-4529-8c29-560bcc08f45c', 'Clean Room', new DateTimeImmutable());

        $todoRepository = $this->createMock(TodoRepositoryInterface::class);
        $todoRepository->expects($this->once())->method('findTodo');
        $todoRepository->expects($this->once())->method('save');

        $handler = new UpdateTodoHandler($todoRepository);
        $handler($command);
    }
}
