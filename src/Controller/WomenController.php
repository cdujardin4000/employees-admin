<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WomenController extends AbstractController
{
    #[Route('/women', name: 'app_women')]
    public function index(): Response
    {
        return $this->render('women/index.html.twig', [
            'controller_name' => 'WomenController',
        ]);
    }
}
