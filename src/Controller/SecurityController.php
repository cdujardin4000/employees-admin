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
    public function switchSecurity(Security $security): Response
    {
    // get the user to be authenticated
       // $user = ...;

            // log the user in on the current firewall
            $security->login($user);

            // if the firewall has more than one authenticator, you must pass it explicitly
            // by using the name of built-in authenticators...
            $security->login($user, 'form_login');
            // ...or the service id of custom authenticators
            $security->login($user, ExampleAuthenticator::class);

            // you can also log in on a different firewall
            $security->login($user, 'form_login', 'other_firewall');

            // ... redirect the user to its account page for instance
    }

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

                ]
        );
    }
}