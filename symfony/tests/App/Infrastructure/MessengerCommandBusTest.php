<?php

declare(strict_types=1);

namespace Tests\App\Infrastructure\Application;

use App\Infrastructure\MessengerCommandBus;
use PHPUnit\Framework\TestCase;
use Tests\App\Fake\SymfonyMessageBus;
use Tests\App\TestDouble\DummyCommand;

final class MessengerCommandBusTest extends TestCase
{
    private SymfonyMessageBus $symfonyMessageBus;
    private MessengerCommandBus $commandBus;

    protected function setUp(): void
    {
        $this->symfonyMessageBus = $this->assembleSymfonyMessageBus();
        $this->commandBus = new MessengerCommandBus($this->symfonyMessageBus);
    }

    public function testMessageForwardedToMessageBusWhileDispatching(): void
    {
        $command = new DummyCommand();
        $this->commandBus->dispatch($command);

        self::assertSame($command, $this->symfonyMessageBus->lastDispatchedCommand());
    }

    private function assembleSymfonyMessageBus(): SymfonyMessageBus
    {
        return new SymfonyMessageBus();
    }
}
