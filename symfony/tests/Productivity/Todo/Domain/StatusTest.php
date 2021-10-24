<?php

declare(strict_types=1);

namespace Tests\Productivity\Todo\Domain;

use PHPUnit\Framework\TestCase;
use Productivity\Todo\Domain\Status;

class StatusTest extends TestCase
{
    public function testStatusCanBeCastedToString(): void
    {
        $status = new Status();
        $status2 = new Status(Status::DONE);

        $this->assertSame(Status::OPEN, $status->toString());
        $this->assertSame(Status::DONE, $status2->toString());
    }
}
