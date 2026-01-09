<?php

namespace App\Controller;

use App\Entity\Chocolate;
use App\Form\ChocolateType;
use App\Repository\ChocolateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chocolate')]
final class ChocolateController extends AbstractController
{
    #[Route(name: 'app_chocolate_index', methods: ['GET'])]
    public function index(ChocolateRepository $chocolateRepository): Response
    {
        return $this->render('chocolate/index.html.twig', [
            'chocolates' => $chocolateRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chocolate_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chocolate = new Chocolate();
        $form = $this->createForm(ChocolateType::class, $chocolate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chocolate);
            $entityManager->flush();

            return $this->redirectToRoute('app_chocolate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chocolate/new.html.twig', [
            'chocolate' => $chocolate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chocolate_show', methods: ['GET'])]
    public function show(Chocolate $chocolate): Response
    {
        return $this->render('chocolate/show.html.twig', [
            'chocolate' => $chocolate,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chocolate_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chocolate $chocolate, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChocolateType::class, $chocolate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chocolate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chocolate/edit.html.twig', [
            'chocolate' => $chocolate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chocolate_delete', methods: ['POST'])]
    public function delete(Request $request, Chocolate $chocolate, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chocolate->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($chocolate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chocolate_index', [], Response::HTTP_SEE_OTHER);
    }
}
