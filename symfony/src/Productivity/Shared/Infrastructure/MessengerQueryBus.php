<?php

declare(strict_types=1);

namespace Productivity\Shared\Infrastructure;

use Productivity\Shared\Application\Query\Interface\Query;
use Productivity\Shared\Application\Query\Interface\QueryBus;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerQueryBus implements QueryBus
{
    use HandleTrait {
        handle as handleQuery;
    }

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /** @return mixed */
    public function handle(Query $query)
    {
        return $this->handleQuery($query);
    }
}
