<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegisterType;
use App\Form\UserVerifyRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Register extends AbstractController
{
    /**
     * @Route("/fundamental/register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        // Build the form
        $user = new User();
        $form = $this->createForm(UserRegisterType::class, $user);

        // Handle the POST submit
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            // Encode the password
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));
            $user->setActive(false);
            $user->setAdmin(0);

            // Save the user to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // Send email, or do something...

            $this->addFlash('userid', $user->getId());
            return $this->redirectToRoute('app_register_verifyregistration');
        }

        return $this->render(
            'fundamental/register.twig', array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/fundamental/register/verify")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function verifyRegistrationAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer) {
        $form = $this->createForm(UserVerifyRegistrationType::class);
        $rawUserid = $this->get('session')->getFlashBag()->get('userid');
        if(!$rawUserid) $user = $this->get('security.token_storage')->getToken()->getUser();
        else $user = $this->getDoctrine()->getRepository(User::class)->find($rawUserid[0]);
        if(!$user || $user == 'anon.') return $this->redirectToRoute('app_login');
        if($user->getActive()) return $this->redirectToRoute('app_display_showme');

        $verificationCode = substr($passwordEncoder->encodePassword($user, $user->getUsername() . $user->getEmail()), 0, 32);

        if(time() - $user->getLastSent() > 180 && (!$form->isSubmitted() && $form->isValid())) {
            $user->setLastSent(time());
            $user->setVerificationCode($verificationCode);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $message = (new \Swift_Message())
                ->setFrom(array('master@mail.cstu.gq' => 'Crystal User System'))
                ->setSubject('Crystal User System - Registration Verification Code')
                ->setTo($user->getEmail())
                ->setBody($this->renderView('verifiyEmail.twig', array(
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                    'code' => $verificationCode
                )), 'text/html');
            $mailer->send($message);
        }

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if($form->getData()['verificationCode'] == $user->getVerificationCode()) {
                $user->setActive(true);
                $user->setVerificationCode(null);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('app_display_showme');
            } else {
                $this->addFlash('reason', 'Not valid verification code.');
                return $this->redirectToRoute('app_display_showerr');
            }
        }
        return $this->render('fundamental/verifyRegistration.twig', array(
            'userid' => $user->getId(),
            'username' => $user->getUsername(),
            'form' => $form->createView()
        ));
    }
}