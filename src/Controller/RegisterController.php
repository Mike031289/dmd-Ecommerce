<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        //si le formulaire est soumis alors:
            //Tu enregistre les datas en BDD
            //Tu envoies un message de confirmation du compte bien créé

        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($user);
            $em->flush();
        }
        return $this->render('register/index.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}
