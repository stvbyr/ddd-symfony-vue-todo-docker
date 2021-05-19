<?php

declare(strict_types=1);

namespace TodoManagement\SingularTodos\Application\Command;

interface HandlerInterface
{
    public function __invoke(CommandInterface $command): void;
}
