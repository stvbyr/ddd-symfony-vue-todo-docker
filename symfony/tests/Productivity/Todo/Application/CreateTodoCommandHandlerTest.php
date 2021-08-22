<?php

declare(strict_types=1);

namespace Tests\Productivity\Todo\Application;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Productivity\Todo\Application\Command\CreateTodoCommand;
use Productivity\Todo\Application\Command\CreateTodoHandler;
use Productivity\Todo\Domain\TodoRepositoryInterface;

final class CreateTodoCommandHandlerTest extends TestCase
{
    public function testTodoItemIsSaved(): void
    {
        $command = new CreateTodoCommand('Clean Room', 'sentou', new DateTimeImmutable());

        $todoRepository = $this->createMock(TodoRepositoryInterface::class);
        $todoRepository->expects($this->once())->method('save');

        $handler = new CreateTodoHandler($todoRepository);
        $handler($command);
    }
}
