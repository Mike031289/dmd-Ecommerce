<?php

namespace App\Controller;

use App\Form\RegisterUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription')]
    public function index(): Response
    {
        $form = $this->createForm(RegisterUserType::class);
        return $this->render('register/index.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}
