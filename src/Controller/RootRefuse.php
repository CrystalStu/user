<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RootRefuse extends AbstractController {
    /**
     * @Route("/")
     */
    public function showAction() {
        return new Response(null, 403);
    }
}