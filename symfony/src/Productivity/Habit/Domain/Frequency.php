<?php

declare(strict_types=1);

namespace Productivity\Habit\Domain;

use Productivity\Habit\Domain\Exception\FrequencyOutOfBoundsException;

class Frequency
{
    public const DAILY = 'daily';
    public const WEEKLY = 'weekly';
    public const MONTHLY = 'monthly';

    public function __construct(
        private string $state = Frequency::DAILY,
    ) {
        if (!in_array($state, [self::DAILY, self::WEEKLY, self::MONTHLY])) {
            throw new FrequencyOutOfBoundsException('Invalid state provided. Refer/Use to the class constanst for valid states.');
        }
    }

    public function toString(): string
    {
        return $this->state;
    }

    public function getIntervalString()
    {
        switch ($this->state) {
            case self::DAILY: return 'P1D';
            case self::WEEKLY: return 'P1W';
            case self::MONTHLY: return 'P1M';
        }
    }
}
