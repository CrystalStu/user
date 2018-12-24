<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class Display extends AbstractController
{
    /**
     * @Route("/display")
     */
    public function showErr() {
        return $this->render("error.twig", array(
            'reason' => "Not logged in.",
        ));
    }

    /**
     * @Route("/display/loginSuccess")
     */
    public function showLoginSuccess() {
        return $this->render("loginSuccess.twig");
    }

    /**
     * @Route("/display/show")
     * @param int id
     * @return Response
     */
    public function showUserDetail() {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if(!$user) $this->redirectToRoute('app_display_showerr');
        return $this->render("display.twig", [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword()
        ]);
    }
}