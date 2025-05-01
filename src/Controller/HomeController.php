<?php

namespace App\Controller;

use App\Service\TraceurService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    private TraceurService $traceur;

    public function __construct(TraceurService $traceurService)
    {
        $this->traceur = $traceurService;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // Page d'accueil
        return $this->render('home/home.html.twig');
    }
}
