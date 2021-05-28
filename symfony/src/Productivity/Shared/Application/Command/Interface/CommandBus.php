<?php

declare(strict_types=1);

namespace Productivity\Shared\Application\Command\Interface;

interface CommandBus
{
    public function dispatch(Command $command): void;
}
