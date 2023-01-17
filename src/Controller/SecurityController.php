<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{


    #[Route('/loginadmin', name: 'app_loginadmin')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'loginadmin/index.html.twig',
                [
                     'last_username' => $lastUsername,
                     'error'         => $error,

                    'translation_domain' => 'admin',

                    'favicon_path' => '/favicon-admin.svg',

                    'page_title' => 'ENCORE CORPS login',

                    'csrf_token_intention' => 'authenticate',


                ]
        );
    }
}