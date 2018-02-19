<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class Login extends Controller
{
    /**
     * @Route("/fundamental/login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils) {

        // get errors
        $error = $authUtils->getLastAuthenticationError();

        // get username
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('fundamental/login.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }
}