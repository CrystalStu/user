<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Display extends Controller
{
    /**
     * @Route("/display")
     */
    public function showAction() {
        return $this->render("display.twig");
    }
}