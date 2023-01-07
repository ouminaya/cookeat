<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController  extends AbstractController
{
    #[Route('/', 'home.index', methods:['GET'])]

    public function index(
        RecipeRepository $recipeRepository
    ): Response
    {
        return $this->render('pages/ingredient/home.html.twig',[
            'recipes' => $recipeRepository->findPublicRecipe(3)
        ]);
    }
}
