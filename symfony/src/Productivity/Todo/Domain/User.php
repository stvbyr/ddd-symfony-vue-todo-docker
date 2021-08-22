<?php

declare(strict_types=1);

namespace Productivity\Todo\Domain;

class User
{
    public function __construct(private string $username)
    {
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
