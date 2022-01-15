<?php

declare(strict_types=1);

namespace Tests\Productivity\Habit\Domain;

use PHPUnit\Framework\TestCase;
use Productivity\Habit\Domain\Exception\FrequencyOutOfBoundsException;
use Productivity\Habit\Domain\Frequency;

class FrequencyTest extends TestCase
{
    public function testFrequencyCanOnlyBeCreatedWithValidIntervals(): void
    {
        try {
            $frequency = new Frequency('this is not a valid interval');

            $this->fail('Should fail because the string above is not a valid interval');
        } catch (FrequencyOutOfBoundsException $e) {
            $this->assertStringStartsWith('Invalid state provided. Refer/Use to the class constanst for valid states.', $e->getMessage());
        }
    }
}
