<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ErrorResolver;
use App\Form\TodoType;
use Productivity\Todo\Application\Command\CreateTodoCommand;
use Productivity\Todo\Application\Command\DeleteTodoCommand;
use Productivity\Todo\Application\Command\UpdateTodoCommand;
use Productivity\Todo\Application\Query\TodoQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class TodoController extends AbstractController
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    #[Route('/todos', name: 'todo.all', methods: ['get'], format: 'json')]
    public function all(TodoQuery $todoQuery, UserInterface $user): Response
    {
        $todos = $todoQuery->findAllByUser($user->getUserIdentifier());

        return $this->json($todos);
    }

    #[Route('/todo/{uuid}', name: 'todo.read', methods: ['get'], format: 'json')]
    public function read(string $uuid, TodoQuery $todoQuery): Response
    {
        $todo = $todoQuery->find($uuid);

        return $this->json($todo);
    }

    #[Route('/todo/create', name: 'todo.create', methods: ['post'], format: 'json')]
    public function create(Request $request, UserInterface $user): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(TodoType::class);

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

    #[Route('/todo/update/{uuid}', name: 'todo.update', methods: ['put'], format: 'json')]
    public function update(string $uuid, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(TodoType::class);

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

    #[Route('/todo/remove/{uuid}', name: 'todo.delete', methods: ['delete'], format: 'json')]
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
