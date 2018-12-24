<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;

class Display extends AbstractController
{
    /**
     * @Route("/display")
     */
    public function rootRedirect() {
        return $this->redirectToRoute("app_display_showme");
    }

    /**
     * @Route("/display/error")
     * @return Response
     */
    public function showErr() {
        $rawReason = $this->get('session')->getFlashBag()->get('reason');
        if($rawReason) $reason = $rawReason[0]; else $reason = "Unknown error.";
        return $this->render("error.twig", array(
            'reason' => $reason
        ));
    }

    /**
     * @Route("/display/loginSuccess")
     */
    public function showLoginSuccess() {
        return $this->render("loginSuccess.twig");
    }

    /**
     * @Route("/display/profile")
     * @return Response
     */
    public function showMe() {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if($user === "anon.") {
            $this->addFlash('reason', 'You have not logged yet.');
            return $this->redirectToRoute('app_display_showerr');
            //return $this->redirectToRoute('app_display_showerr', array('reason' => 'You have not logged yet.'));
        }
        return $this->render("display.twig", [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword()
        ]);
    }

    /**
     * @Route("/display/profile/{id}")
     */
    public function showOthers($id) {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if(!$user) return $this->redirectToRoute('app_display_showerr', array('reason' => 'This user ID does not exist.'));
        return $this->render("display.twig", [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword()
        ]);
    }
}