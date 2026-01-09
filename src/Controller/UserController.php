<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/profil/edit', name: 'user_edit_current')]
    public function editCurrent(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            $this->addFlash('success', 'Profil mis à jour !');
            return $this->redirectToRoute('app_profile');
        }
        
        return $this->render('user/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        // Vérifie qu'un utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Récupère l'utilisateur connecté
        $user = $this->getUser();
        
        // Si aucun utilisateur n'est connecté, redirige vers login
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/profile.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    // Dans un contrôleur existant ou crée TestController.php
    #[Route('/check-user', name: 'app_check_user')]
    public function checkUser(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $em->getRepository(User::class)->findOneBy(['email' => 'a@b.com']);
        
        if (!$user) {
            return new Response('Utilisateur NON TROUVÉ');
        }
        
        // Vérifie le mot de passe
        $plainPassword = 'a@b.com';
        $isValid = $passwordHasher->isPasswordValid($user, $plainPassword);
        
        return new Response(sprintf(
            'Utilisateur trouvé:<br>
            ID: %s<br>
            Email: %s<br>
            Mot de passe hashé: %s<br>
            Le mot de passe "a@b.com" est valide ? %s<br>
            Longueur du hash: %s',
            $user->getId(),
            $user->getEmail(),
            $user->getPassword(),
            $isValid ? 'OUI' : 'NON',
            strlen($user->getPassword())
        ));
    }
}
