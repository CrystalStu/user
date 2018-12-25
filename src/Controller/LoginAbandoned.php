<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserLoginType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginAbandoned extends AbstractController {

    /**
     * @Route("fundamental/login/abandoned")
     * @param Request         $request
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function loginAction(Request $request, LoggerInterface $logger) {
        // Build the form
        $user = new User();
        $form = $this->createForm(UserLoginType::class, $user);

        // Handle the POST request
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $username = $request->get('username');
            // Encode the password
            $password = $this->get('security.password_encoder')->encodePassword($user, $request->get('plainPassword'));
            $repository = $this->getDoctrine()->getRepository(User::class);
            // return Response::create("break");
            $user = $repository->findOneBy([
                'username' => $username,
                'password' => $password
            ]);
            if ($user) {
                $userId = $user->getId();
                // Set session
                $session = sha1($userId . $username . $password . date("m/d/Y") . time());
                $user->setSession($session);
                setcookie('user', $user->getSession(), 2592000, null, '.cstu.gq', true);
                if ($_ENV == 'dev') {
                    setcookie('user', $user->getSession(), 2592000, null, '127.0.0.1', true);
                    setcookie('user', $user->getSession(), 2592000, null, 'localhost', true);
                }
                return $this->redirectToRoute('app_display_show' . $user->getId());
            }
        }

        return $this->render('login_abandoned.twig', array(
            'form' => $form->createView()
        ));
    }
}