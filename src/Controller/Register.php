<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Register extends AbstractController
{
    /**
     * @Route("/fundamental/register")
     */
    public function registerAction(Request $request) {
        // Build the form
        $user = new User();
        $form = $this->createForm(UserRegisterType::class, $user);

        // Handle the POST submit
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            // Encode the password
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setActive(true);
            $user->setAdmin(0);

            // Save the user to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // Send email, or do something...

            // return $this->redirectToRoute('app_register_new');
        }

        return $this->render(
            'fundamental/register.twig', array(
                'form' => $form->createView()
            )
        );
    }
    /*public function new(Request $request) {
        $task = new User();
        // $task->setUsername('Username');
        // $task->setPassword('Password');

        $form = $this->createFormBuilder($task)
            ->add("Username", TextType::class)
            ->add("Password", PasswordType::class)
            ->add('Register', SubmitType::class, array(
                'label' => 'Register'
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            return $this->redirectToRoute('task_success');
        }

        return $this->render('fundamental/register.twig', array(
            'form' => $form->createView(),
        ));
    }*/
}