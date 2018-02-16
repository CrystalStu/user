<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Register extends Controller
{
    /**
     * @Route("/fundamental/register")
     */
    public function new(Request $request) {
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
    }
}