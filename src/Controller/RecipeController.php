<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    /**
     * this controller display all recipes
     *
     * @param RecipeRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/recette', name: 'recipe.index', methods:('GET'))]
    #[IsGranted('ROLE_USER')]
    public function index(
        RecipeRepository $repository,
         PaginatorInterface $paginator,
          Request $request
          ): Response
    {
        $recipes = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }



    #[Route('/recette/publique', 'recipe.index.public', methods: ['GET'])]
    public function indexPublic(
        RecipeRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ) : Response
    {
        $recipes = $paginator->paginate(
            $repository->findPublicRecipe(0),
            $request->query->getInt('page', 1),10

        );
        return $this->render('pages/recipe/index_public.html.twig', [
            'recipes' => $recipes,
        ]);
    
        
    }

    /**
     *This controller allow us to see a recipe if this one is public
     *
     * @param Recipe $recipe
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and recipe.getisPublic() === true")]
    #[Route('/recette/{id}','recpie.show', methods: ['GET'])]

    
    public function show(Recipe $recipe) : Response
    {
        return $this->render('pages/recipe/show.html.twig', [
            'recipe'=>$recipe
        ]);
    }

    /**
     * this controller allow us to create a new recipe
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recette/creation', 'recipe.new', methods: ['GET', 'POST'])]
    
    public function new(Request $request, EntityManagerInterface $manager) : Response
    {
        $recipe = new Recipe();
        $form = $this->createform(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $recipe = $form->getData();
            $recipe->setUser($this->getUser());

            $manager->persist($recipe);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre recette a été crée avec succès !'
            );


            return $this->redirectToRoute('recipe.index');


        }
        return $this->render('pages/recipe/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #
    #[Route('/recette/edition/{id}', 'recette.edit', methods: ['GET', 'POST'])]

    /**
     * this controller allow us to édit a new recipe
     * @param Recipe $recipe
     * @param Request $request
     * @param EntityManagerInterface $Manager
     * @return Response
     */
    
    public function edit(
        Recipe $recipe,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();

            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été modifié avec succès !'
            );

            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/recette/suppression/{id}', 'recipe.delete', methods:['GET'])]
    /**
     *This controller allow us delete an recipe
     * @param EntityManagerInterface $manager
     * @param Recipe $recipe
     * @return Response
     */
    public function delete(
        EntityManagerInterface $manager,
         Recipe $recipe
         ) : Response{
        $manager->remove($recipe);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre recette a été supprimé avec succès !'
        );

        return $this->redirectToRoute('recipe.index');

    }
    
}
