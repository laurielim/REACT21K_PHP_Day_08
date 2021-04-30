<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeScreenController extends AbstractController
{
    #[Route('/home', name: 'home_screen')]
    public function index(Request $request): Response
    {
        return $this->json([
            // Universal way to get parameters inside symfony:
            'message' => $request->query->get( 'page'),
            'path' => 'src/Controller/HomeScreenController.php',
        ]);
    }

//    Use name inside of routes to be able to redirect
   #[Route('/other')]
    public function other() {
        return $this ->redirectToRoute(route:'home_screen');
    }

    #[Route('/recipes/all', name: 'get_all_recipes', methods: ['GET'])]
    public function getAllRecipes(Request $request): Response
    {
        $rootPath = $this->getParameter('kernel.project_dir');
        $recipes = file_get_contents($rootPath.'/resources/recipes.json');
        $decodedRecipes = json_decode($recipes, true);
        return $this->json($decodedRecipes);
    }

    #[Route('/recipes/{id}', name: 'get_a_recipe', methods: ['GET'])]
    public function recipes($id, Request $request): Response
    {
        return $this->json([
            // Universal way to get parameters inside symfony:
            'message' => 'Requesting recipe with id ' . $id,
            'page' => $request->query->get('page')
        ]);
    }

}

