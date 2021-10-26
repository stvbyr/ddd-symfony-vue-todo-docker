<?php

declare(strict_types=1);

namespace Tests\Productivity\Todo\Domain;

use PHPUnit\Framework\TestCase;
use Productivity\Todo\Domain\User;

class UserTest extends TestCase
{
    public function testCanGetUsername(): void
    {
        $user = new User('John Doe');

        $this->assertSame('John Doe', $user->getUsername());
    }
}
