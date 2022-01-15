<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Productivity\Todo\Application\Command\CreateTodoCommand;
use Symfony\Component\Messenger\MessageBusInterface;

class TodoFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = $manager->getRepository(User::class)->findOneBy(['username' => 'admin']);

        $command = new CreateTodoCommand(
            'Test Todo',
            $user->getUserIdentifier(),
            new DateTimeImmutable('today'),
        );
        $this->messageBus->dispatch($command);
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
        ];
    }
}
