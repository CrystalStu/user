<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Display extends Controller
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
     */
    public function showUserDetail($id) {
        return $this->render("display.twig", array(
            'id' => $id,
            'username' => 'TURX',
            'password' => '',
        ));
    }
}