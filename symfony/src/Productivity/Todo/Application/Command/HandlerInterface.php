<?php

declare(strict_types=1);

namespace Productivity\Todo\Application\Command;

interface HandlerInterface
{
    public function __invoke(CommandInterface $command): void;
}
