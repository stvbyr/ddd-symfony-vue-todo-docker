<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ErrorResolver;
use App\Form\TodoType;
use Productivity\Todo\Application\Command\CreateTodoCommand;
use Productivity\Todo\Application\Command\UpdateTodoCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    #[Route('/todo/create', name: 'todo.create', methods: ['post'], format: 'json')]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(TodoType::class);

        $form->submit($data);

        if ($form->isValid()) {
            $command = new CreateTodoCommand(
                $form->get('title')->getData(),
                1,
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
}
