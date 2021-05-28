<?php

declare(strict_types=1);

namespace Productivity\Todo\Domain;

class User
{
    public function __construct(private int $id)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
