<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TasksController extends AbstractController
{
    #[Route('/tasks/all', name: 'tasks', methods: ['GET'])]
    public function getAllTasks(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TasksController.php',
        ]);
    }

    #[Route('/tasks/{id}', name: 'task', methods: ['GET'])]
    public function getTask($id): Response
    {
        return $this->json([
            'message' => 'Task' . $id,
            'path' => 'src/Controller/TasksController.php',
        ]);
    }

    #[Route('/tasks/add', name: 'task', methods: ['POST'])]
    public function addTask($id): Response
    {
        return $this->json([
            'message' => 'Task' . $id,
            'path' => 'src/Controller/TasksController.php',
        ]);
    }
}
