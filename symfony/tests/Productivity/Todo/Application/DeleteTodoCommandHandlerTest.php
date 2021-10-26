<?php

declare(strict_types=1);

namespace Tests\Productivity\Todo\Application;

use PHPUnit\Framework\TestCase;
use Productivity\Todo\Application\Command\DeleteTodoCommand;
use Productivity\Todo\Application\Command\DeleteTodoHandler;
use Productivity\Todo\Domain\TodoRepositoryInterface;

final class DeleteTodoCommandHandlerTest extends TestCase
{
    public function testTodoItemIsDeleted(): void
    {
        $command = new DeleteTodoCommand('cd28a79b-511d-4529-8c29-560bcc08f45c');

        $todoRepository = $this->createMock(TodoRepositoryInterface::class);
        $todoRepository->expects($this->once())->method('remove');

        $handler = new DeleteTodoHandler($todoRepository);
        $handler($command);
    }
}
