<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {//die;
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
       // dump($error);
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
       // dump($lastUsername);die();
        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,

        ]);
    }


    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
