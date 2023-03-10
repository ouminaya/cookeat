<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends AbstractController
{


    /**
     *this controller allow us to edit user's profile
     * @param User $choosenUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user === choosenUser")]
    #[Route('/utilisateur/edition/{id}', name: 'user.edit', methods: ['GET', 'POST'])]
   
    public function edit(
        User $choosenUser,
        Request $request, 
        EntityManagerInterface $manager
     ): Response
    { //si utilisateur n'est pas connecter renvoyer vers le formulaire de création de compte
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_register');
        }
        //si l'utilisateur est connecter revoyer à ses recettes
        
        if ($this->getUser() !== $choosenUser) {
            return $this->redirectToRoute('recipe.index');
        }

        $form = $this->createForm(UserType::class, $choosenUser);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Les informations de votre compte ont bien été modifiées.'
            );

            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

      /** 
     *This controller allow us to edit user's password
     * @param User $choosenUser
     * @param Request $request
     * @param UserPasswordHasherInterface $hasher
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user === choosenUser")]
    #[Route('/utilisateur/edition-mot-de-passe/{id}', 'user.edit.password', methods: ['GET', 'POST'])]

    public function editPassword(
        User $choosenUser,
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $manager
    ): Response {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_register');
        }
        //si l'utilisateur est connecter revoyer à ses recettes
        
        if ($this->getUser() !== $choosenUser) {
            return $this->redirectToRoute('recipe.index');
        }

        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($hasher->isPasswordValid($choosenUser, $form->get('plainPassword')->getData())) {
                $choosenUser->setPassword(
                    $hasher->hashPassword(
                        $$choosenUser,
                        $form->getData()['newPassword']
                    )
                );
                
                $manager->persist($choosenUser);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Le mot de passe a été modifié.'
                );
                return $this->redirectToRoute('recipe.index');
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe est incorrect.'
                );
            }
        }
        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView()

        ]);
    }
}
