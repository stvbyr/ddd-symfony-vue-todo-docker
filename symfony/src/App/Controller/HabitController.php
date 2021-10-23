<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ErrorResolver;
use App\Form\HabitType;
use Productivity\Habit\Application\Command\CreateHabitCommand;
use Productivity\Habit\Application\Command\DeleteHabitCommand;
use Productivity\Habit\Application\Command\UpdateHabitCommand;
use Productivity\Habit\Application\Query\HabitQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HabitController extends AbstractController
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    #[Route('/habit/{uuid}', name: 'habit.read', methods: ['get'], format: 'json')]
    public function read(string $uuid, HabitQuery $habitQuery): Response
    {
        $habit = $habitQuery->find($uuid);

        return $this->json($habit);
    }

    #[Route('/habit/create', name: 'habit.create', methods: ['post'], format: 'json')]
    public function create(Request $request, UserInterface $user): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(HabitType::class);

        $form->submit($data);

        if ($form->isValid()) {
            $command = new CreateHabitCommand(
                $form->get('title')->getData(),
                $user->getUserIdentifier(),
                [
                    'from' => $form->get('fromDate')->getData(),
                    'to' => $form->get('toDate')->getData(),
                    'frequency' => $form->get('frequency')->getData(),
                ]
            );
            $this->messageBus->dispatch($command);

            return $this->json(['message' => 'Habit created successful']);
        }

        return $this->json(['message' => 'The Habit could not be created', 'errors' => ErrorResolver::getErrorsFromForm($form, true)], 400);
    }

    #[Route('/habit/update/{uuid}', name: 'habit.update', methods: ['put'], format: 'json')]
    public function update(string $uuid, Request $request, UserInterface $user): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(HabitType::class);

        $form->submit($data);

        if ($form->isValid()) {
            $command = new UpdateHabitCommand(
                $uuid,
                $form->get('title')->getData(),
                $user->getUserIdentifier(),
                [
                    'from' => $form->get('fromDate')->getData(),
                    'to' => $form->get('toDate')->getData(),
                    'frequency' => $form->get('frequency')->getData(),
                ],
            );
            $this->messageBus->dispatch($command);

            return $this->json(['message' => 'Habit updated successful']);
        }

        return $this->json(['message' => 'The Habit could not be updated'], 400);
    }

    #[Route('/habit/remove/{uuid}', name: 'habit.remove', methods: ['delete'], format: 'json')]
    public function delete(string $uuid): Response
    {
        try {
            $command = new DeleteHabitCommand($uuid);
            $this->messageBus->dispatch($command);
        } catch (\Exception $e) {
            return $this->json(['message' => 'The Habit could not be removed'], 400);
        }

        return $this->json(['message' => 'Habit removed successful']);
    }
}
