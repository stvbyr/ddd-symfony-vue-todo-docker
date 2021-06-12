<?php

namespace Productivity\Habit\Domain;

class Frequency
{
    public const DAILY = 'daily';
    public const WEEKLY = 'weekly';
    public const MONTHLY = 'monthly';

    public function __construct(
        private string $state = Frequency::DAILY,
    ) {
    }

    public function toString(): string
    {
        return $this->state;
    }
}
