<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ErrorResolver;
use App\Form\HabitType;
use Productivity\Todo\Application\Command\CreateTodoCommand;
use Productivity\Todo\Application\Command\DeleteTodoCommand;
use Productivity\Todo\Application\Command\UpdateTodoCommand;
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

    #[Route('/habit/create', name: 'habit.create', methods: ['post'], format: 'json')]
    public function create(Request $request, UserInterface $user): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(HabitType::class);

        $form->submit($data);

        if ($form->isValid()) {
            $command = new CreateTodoCommand(
                $form->get('title')->getData(),
                $user->getUserIdentifier(),
                $form->get('scheduledDate')->getData(),
            );
            $this->messageBus->dispatch($command);

            return $this->json(['message' => 'Todo created successful']);
        }

        return $this->json(['message' => 'The Todo could not be created', 'errors' => ErrorResolver::getErrorsFromForm($form, true)], 400);
    }

    #[Route('/habit/update/{uuid}', name: 'todo.update', methods: ['put'], format: 'json')]
    public function update(string $uuid, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(HabitType::class);

        $form->submit($data);

        if ($form->isValid()) {
            $command = new UpdateTodoCommand(
                $uuid,
                $form->get('title')->getData(),
                $form->get('scheduledDate')->getData(),
            );
            $this->messageBus->dispatch($command);

            return $this->json(['message' => 'Todo updated successful']);
        }

        return $this->json(['message' => 'The Todo could not be updated'], 400);
    }

    #[Route('/habit/remove/{uuid}', name: 'todo.remove', methods: ['delete'], format: 'json')]
    public function delete(string $uuid): Response
    {
        try {
            $command = new DeleteTodoCommand($uuid);
            $this->messageBus->dispatch($command);
        } catch (\Exception $e) {
            return $this->json(['message' => 'The Todo could not be removed'], 400);
        }

        return $this->json(['message' => 'Todo removed successful']);
    }
}
