<?php

declare(strict_types=1);

namespace Tests\App;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\DatabasePrimer;

class TodoTest extends KernelTestCase
{
    protected EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        self::bootKernel();
        $kernel = self::$kernel;

        DatabasePrimer::prime($kernel);

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testOne(): void
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $this->assertSame(1, count($users));
    }
}
