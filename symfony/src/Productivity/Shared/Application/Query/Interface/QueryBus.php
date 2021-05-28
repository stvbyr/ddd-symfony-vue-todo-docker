<?php

declare(strict_types=1);

namespace Productivity\Shared\Application\Query\Interface;

interface QueryBus
{
    /** @return mixed */
    public function handle(Query $query);
}
