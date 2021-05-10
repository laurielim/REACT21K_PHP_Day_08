<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\RecipeList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeScreenController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {

        return new Response('<h1>Hello World</h1>');

    }


    #[Route('/home', name: 'home_screen')]
    public function home(Request $request): Response
    {
        return $this->json([
            // Universal way to get parameters inside symfony:
            'message' => $request->query->get( 'page'),
            'path' => 'src/Controller/HomeScreenController.php',
        ]);
    }

//    Use name inside of routes to be able to redirect
   #[Route('/other')]
    public function other(): Response {
        return $this->redirectToRoute(route:'home_screen');
    }

    #[Route('/recipes/all', name: 'get_all_recipes', methods: ['GET'])]
    public function getAllRecipes(Request $request): Response
    {
        /*
        // Get Recipes from JSON
        $rootPath = $this->getParameter('kernel.project_dir');
        $recipes = file_get_contents($rootPath.'/resources/recipes.json');
        $decodedRecipes = json_decode($recipes, true);
        return $this->json($decodedRecipes);*/

        // Get Recipes from SQLite db
//        $recipes = $this->getDoctrine()->getRepository(Recipe::class)->findAll();
        $recipes = $this->getDoctrine()->getRepository(RecipeList::class)->findAll();
        $response=[];
        // Get data of each instance of class Recipe
        foreach($recipes as $recipe) {
            $response [] = array(
                'name'=>$recipe->getName(),
                'description'=>$recipe->getDescription(),
                'ingredients'=>$recipe->getIngredients(),
                'instructions'=>$recipe->getInstructions()
//                'difficulty'=>$recipe->getDifficulty()
            );
        }
        return $this->json($response);
    }

    #[Route('/recipes/add', name: 'add_new_recipe', methods:["POST"])]
    public function addRecipe(Request $request): Response
    {
        // Entity managers helps manage db
        $entityManager = $this->getDoctrine()->getManager();

        // Get POSTED data
        /*$name = $request->query->get('name');
        $ingredients = $request->query->get('ingredients');
        $difficulty = $request->query->get('difficulty');

        $newRecipe = new Recipe();
        $newRecipe->setName($name);
        $newRecipe->setIngredients($ingredients);
        $newRecipe->setDifficulty($difficulty);*/

 /*       $response [] = array(
            'name'=>$newRecipe->getName(),
            'ingredients'=>$newRecipe->getIngredients(),
            'difficulty'=>$newRecipe->getDifficulty()
        );*/

        $data = json_decode($request->getContent(), true);

        $response [] = array(
            'name'=>$data['name'],
            'description'=>$data['desc'],
            'ingredients'=>$data['ingredients'],
            'instructions'=>$data['instructions']
        );

/*            $name=$data['name'];
            $description=$data['desc'];
            $ingredients=$data['ingredients'];
            $instructions=$data['instructions'];*/

        $newRecipe = new RecipeList();
        $newRecipe->setName($data['name']);
        $newRecipe->setDescription($data['desc']);
        $newRecipe->setIngredients($data['ingredients']);
        $newRecipe->setInstructions($data['instructions']);

       $entityManager->persist($newRecipe);
       $entityManager->flush();


      return new Response('trying to add new recipe...' . $newRecipe->getId());
//         return new Response(json_encode($newRecipe->getInstructions()));
    }

/*    #[Route('/recipes/{id}', name: 'get_a_recipe', methods: ['GET'])]
    public function recipes($id, Request $request): Response
    {
        return $this->json([
            // Universal way to get parameters inside symfony:
            'message' => 'Requesting recipe with id ' . $id,
            'page' => $request->query->get('page')
        ]);
    }*/

    #[Route('/recipes/find/{id}', name: 'find_a_recipe', methods: ['GET'])]
    public function findRecipe($id): Response {
        $recipe = $this->getDoctrine()->getRepository(Recipe::class)->find($id);

        if (!$recipe) {
            throw $this->createNotFoundException(
                'No recipe was found with the id: ' . $id
            );
        } else {
            return $this->json([
                'id' => $recipe->getId(),
                'name' => $recipe->getName(),
                'ingredients' => $recipe->getIngredients(),
                'difficulty' => $recipe->getDifficulty()
            ]);
        }
    }

    #[Route('/recipes/edit/{id}/{name}', name: 'edit_a_recipe', methods: ['PUT'])]
    public function editRecipe($id, $name): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $recipe = $this->getDoctrine()->getRepository(Recipe::class)->find($id);

        if (!$recipe) {
            throw $this->createNotFoundException(
                'No recipe was found with the id: ' . $id
            );
        } else {
            $recipe->setName($name);
            $entityManager->flush();

            return $this->json([
                'message' => 'Edited a recipe with id ' . $id
            ]);
        }
    }

    #[Route('/recipes/remove/{id}', name: 'REMOVE_a_recipe', methods: ['DELETE'])]
    public function removeRecipe($id):Response {
        $entityManager = $this->getDoctrine()->getManager();
        $recipe = $this->getDoctrine()->getRepository(Recipe::class)->find($id);

        if (!$recipe) {
            throw $this->createNotFoundException(
                'No recipe was found with the id: ' . $id
            );
        } else {
            $entityManager->remove($recipe);
            $entityManager->flush();

            return $this->json([
                'message' => 'Removed the recipe with id ' . $id
            ]);
        }
    }

}

