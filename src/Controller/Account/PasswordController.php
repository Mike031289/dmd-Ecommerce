<?php

namespace App\Controller\Account;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class PasswordController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        // Constructor can be used for dependency injection if needed
        $this->em = $em;
    }   
    
    #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')]
   public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
   {
      $user = $this->getUser();
      $form = $this->createForm(PasswordUserType::class, $user, [
         'passwordHasher' => $passwordHasher
      ]);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
         $this->em->flush();

         $this->addFlash(
            'success',
            'Votre mot de passe est correctement mis à jour'
         );

      }
      return $this->render('account/password/index.html.twig', [
         'modifyPwd' => $form->createView()
      ]);
   }
}