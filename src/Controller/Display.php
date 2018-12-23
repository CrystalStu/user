<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Display extends AbstractController
{
    /**
     * @Route("/display")
     */
    public function showErr() {
        return $this->render("error.twig", array(
            'reason' => "Did not give a valid fundamental id",
        ));
    }

    /**
     * @Route("/display/{id}")
     * @Security("has_role('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function showUserDetail($id) {
        return $this->render("display.twig", [
            'id' => $id,
            'username' => 'T',
            'password' => '',
        ]);
    }
}