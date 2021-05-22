<?php

declare(strict_types=1);

namespace Productivity\Todo\Application\Command;

use Productivity\Todo\Application\Command\Interface\Command;
use Productivity\Todo\Application\Command\Interface\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerCommandBus implements CommandBus
{
    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function dispatch(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
