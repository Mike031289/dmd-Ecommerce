<?php

namespace App\Controller;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\AddressUserType;

final class AccountController extends AbstractController
{
   #[Route('/compte', name: 'app_account')]
   public function index(): Response
   {
      return $this->render('account/index.html.twig');
   }

   #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')]
   public function password(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
   {
      $user = $this->getUser();
      $form = $this->createForm(PasswordUserType::class, $user, [
         'passwordHasher' => $passwordHasher
      ]);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
         $em->flush();

         $this->addFlash(
            'success',
            'Votre mot de passe est correctement mis à jour'
         );

      }
      return $this->render('account/password.html.twig', [
         'modifyPwd' => $form->createView()
      ]);
   }
   
    #[Route('/compte/adresses', name: 'app_account_addresses')]
    public function addresses(): Response
    {
        return $this->render('account/addresses.html.twig');
    }
    
    #[Route('/compte/adresse/ajouter', name: 'app_account_address_form')]
    public function addressForm(Request $request): Response
    {
        $from = $this->createForm(AddressUserType::class);
        $from->handleRequest($request);
        
        return $this->render('account/addressForm.html.twig', [
           'addressForm' => $from
        ]);
    }
}