<?php

namespace App\Controller;

use App\Repository\IngredientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'app_ingredient')]
    public function index(IngredientsRepository $repository): Response
    {
        
        return $this->render('pages/ingredient/index.html.twig', ['ingredients' => $repository->findAll()]);
    }
}
