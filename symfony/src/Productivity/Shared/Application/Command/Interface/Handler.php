<?php

declare(strict_types=1);

namespace Productivity\Shared\Application\Command\Interface;

interface Handler
{
    public function __invoke(Command $command): void;
}