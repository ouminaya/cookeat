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
     *this function controller all ingredients
     * @param IngredientsRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'app_ingredient', methods: ['GET'])]
    public function index(IngredientsRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $ingredients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('pages/ingredient/index.html.twig', ['ingredients' => $ingredients]);
    }

    /**
     * this controller show a form which create an ingredient
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Response 
     */
    #[Route('/ingredient/nouveau', 'ingredient.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $ingredients = new Ingredients;
        $form = $this->createForm(IngredientType::class, $ingredients);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredients = $form->getData();
            $manager->persist($ingredients);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédients a été crée avec succès !'
            );

            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * this controller show a form which edit an ingredient
     * @param IngredientsRepository $repository
     * @param int $id
     * @return Response 
     */
    #[Route('/ingredient/edition/{id}', 'ingredient.edit', methods: ['GET', 'POST'])]
    /**
     *THis controller allow us delete an ingredient
     * @param IngredientsRepository $repository
     * @param integer $id
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(
        IngredientsRepository $repository,
        int $id,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $ingredients = $repository->findOneBy(["id" => $id]);
        $form = $this->createForm(IngredientType::class, $ingredients);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredients = $form->getData();
            $manager->persist($ingredients);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédients a été modifié avec succès !'
            );

            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    

    /**
     * THis controller allow us delete an ingredient
     * @param Ingredient $ingredient
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Response
     */
    #[Route('/ingredient/suppression/{id}', 'ingredient.delete', methods:['GET'])]
     
    public function delete(
        EntityManagerInterface $manager,
         Ingredients $ingredients
         ) : Response{
        $manager->remove($ingredients);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre ingrédients a été supprimé avec succès !'
        );

        return $this->redirectToRoute('app_ingredient');

    }
}
