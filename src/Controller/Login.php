<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserLoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class Login extends Controller
{

    /**
     * @Route("fundamental/login")
     */
    public function loginAction(Request $request) {
        // Build the form
        $user = new User();
        $form = $this->createForm(UserLoginType::class, $user);

        // Handle the POST request
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $username = $request->get('username');
            // Encode the password
            $password = $this->get('security.password_encoder')->encodePassword($user, $request->get('plainPassword'));
            $repository = $this->getDoctrine()->getRepository(User::class);
            // return Response::create("break");
            $user = $repository->findOneBy([
                'username' => $username,
                'password' => $password
            ]);
            if($user) {
                $userId = $user->getId();
                // Set session
                $session = sha1($userId . $username . $password . date() . time());
                $user->setSession($session);
                setcookie('user', $user->getSession(), 2592000, null,'.cstu.gq', true);
                return $this->redirectToRoute('/display/' . $user->getId());
            }
        }

        return $this->render(
            'fundamental/login.twig', array(
                'form' => $form->createView()
            )
        );
    }
}