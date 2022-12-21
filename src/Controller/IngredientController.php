<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Form\IngredientType;
use App\Repository\IngredientsRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientController extends AbstractController
{
    /**
     *this function display all ingredients
     * @param IngredientsRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'app_ingredient', methods:['GET'])]
    public function index(IngredientsRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $ingredients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('pages/ingredient/index.html.twig', ['ingredients' => $ingredients]);
    }

    #[Route('/ingredient/nouveau', 'ingredient.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
        ): Response
    {
        $ingredients = new Ingredients;
        $form = $this->createForm(IngredientType::class, $ingredients);

        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $ingredients = $form->getData();

            $manager->persist($ingredients);
            $manager->flush();

            $this->redirectToRoute('ingredient.index');


        }else{

        }
        return $this->render('pages/ingredient/new.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
