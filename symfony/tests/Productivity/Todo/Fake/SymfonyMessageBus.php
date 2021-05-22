<?php

declare(strict_types=1);

namespace Tests\Productivity\Todo\Fake;

use Productivity\Todo\Application\Command\Interface\Command;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyMessageBus implements MessageBusInterface
{
    private Command $dispatchedCommand;

    public function dispatch($message, array $stamps = []): Envelope
    {
        $this->dispatchedCommand = $message;

        return new Envelope($message);
    }

    public function lastDispatchedCommand(): Command
    {
        return $this->dispatchedCommand;
    }
}
