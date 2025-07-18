<?php

namespace App\Controller;

use App\Form\PasswordUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\AddressUserType;
use App\Entity\Address;

final class AccountController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        // Constructor can be used for dependency injection if needed
        $this->em = $em;
    }   
        
   #[Route('/compte', name: 'app_account')]
   public function index(): Response
   {
      return $this->render('account/index.html.twig');
   }

   #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')]
   public function password(Request $request, UserPasswordHasherInterface $passwordHasher): Response
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
      return $this->render('account/password.html.twig', [
         'modifyPwd' => $form->createView()
      ]);
   }
   
    #[Route('/compte/adresses', name: 'app_account_addresses')]
    public function addresses(): Response
    {
        // Render the addresses in a Twig template
        return $this->render('account/addresses.html.twig');
    }
    
    #[Route('/compte/adresses/delete/{id}', name: 'app_account_address_delete')]
    public function addressDelete($id, AddressRepository $addressRepository): Response
    {
        // Fetch the address by ID, ensuring it belongs to the current user
        $address = $addressRepository->findOneById($id);
        // Check if the address exists and belongs to the current user
        if (!$address || $address->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_account_addresses');
        } else {
            // If the address exists and belongs to the user, proceed with deletion
            $this->em->remove($address);
            $this->em->flush();
        
            // Flash a success message
            $this->addFlash(
            'success',
            'Votre adresse a été supprimée avec succès.'
            );
            
            // Redirect to the addresses page after deletion
            return $this->redirectToRoute('app_account_addresses');
        } 
    }
    
    #[Route('/compte/adresse/ajouter/{id}', name: 'app_account_address_form', defaults: ['id' => null])]
    public function addressForm(Request $request, $id, AddressRepository $addressRepository): Response
    {
        // If an ID is provided, you might want to fetch the existing address
        if ($id) {
            // Fetch the address by ID, ensuring it belongs to the current user
            $address = $addressRepository->findOneById($id);
            // Check if the address exists and belongs to the current user
            if (!$address || $address->getUser() !== $this->getUser()) {
               return $this->redirectToRoute('app_account_addresses');
            }
        } else {
            // If no ID, create a new address
            $address = new Address();
            $address->setUser($this->getUser());
        }
        // Address found and belongs to the user, proceed with the form
        // Create the form for adding a new address
        $from = $this->createForm(AddressUserType::class, $address);
        $from->handleRequest($request);
        
        if ($from->isSubmitted() && $from->isValid()) {
            // Here you would typically save the address to the database
            $this->em->persist($address);
            $this->em->flush();
            
            // For now, we just flash a success message
            $this->addFlash(
                'success',
                'Votre adresse a été correctement sauvegardée.'
            );
            
            // Redirect to the addresses page or wherever appropriate
            return $this->redirectToRoute('app_account_addresses');
        }
        
        return $this->render('account/addressForm.html.twig', [
           'addressForm' => $from
        ]);
    }
}