<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PageController extends AbstractController
{
    #[Route('/page', name: 'app_page')]
    public function index(): Response
    {
        return $this->render('pages/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/', name:'home')]
    public function home(): Response {
        return $this->render('pages/home.html.twig');
    }

    #[Route('/mentions-legales', name:'mentions')]
    public function mentions(): Response {
        return $this->render('pages/mentions.html.twig');
    }

    #[Route('/cgu', name:'cgu')]
    public function cgu(): Response {
        return $this->render('pages/cgu.html.twig');
    }

    #[Route('/confidentialite', name:'confidentialite')]
    public function confidentialite(): Response {
        return $this->render('pages/confidentialite.html.twig');
    }

    #[Route('/qui-sommes-nous', name:'about')]
    public function about(): Response {
        return $this->render('pages/about.html.twig');
    }
}
