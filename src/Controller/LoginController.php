<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
   #[Route('/connexion', name: 'app_login')]
   public function index(AuthenticationUtils $authenticationUtils): Response
   {
      //Gérer les erreur d'auth
      $error = $authenticationUtils->getLastAuthenticationError();

      //Dernier username (email)
      $lastUserName = $authenticationUtils->getLastUsername();

      return $this->render('login/index.html.twig', [
         'error'         => $error,
         'last_username' => $lastUserName,
      ]);
   }

   #[Route(path: '/deconnexion', name: 'app_logout', methods: ['GET'])]
   public function logout(): never
   {
      //controller can be blank: it will never be called !
      throw new \Exception(message: 'Don\'t forget to activate logout in security.yaml');
   }
}