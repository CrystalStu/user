<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Display extends AbstractController {
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
        if ($rawReason) $reason = $rawReason[0]; else $reason = "Unknown error.";
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if (!$user->getActive()) {
            $this->addFlash('userid', $user->getId());
            return $this->redirectToRoute('app_register_verifyregistration');
        }
        $tGrp = $user->getGrp();
        if (!sizeof($tGrp)) {
            $tGrp = array(
                'None.'
            );
        } else {
            for ($t = 0; $t < sizeof($tGrp) - 1; $t++) {
                $tGrp[$t] .= ', '; // Add a comma after every element except the end
            }
            if (sizeof($tGrp) > 1) $tGrp[sizeof($tGrp) - 2] .= 'and ';
            $tGrp[sizeof($tGrp) - 1] .= '.';
        }
        return $this->render("display.twig", [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'admin' => $user->getAdmin(),
            'grp' => $tGrp
        ]);
    }

    /**
     * @Route("/display/profile/{id}")
     * @param int $id
     * @return Response
     */
    public function showOthers($id) {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if (!$user) {
            $this->addFlash('reason', 'This user ID does not exist.');
            return $this->redirectToRoute('app_display_showerr');
        }
        return $this->render("display.twig", [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'admin' => $user->getAdmin()
        ]);
    }
}