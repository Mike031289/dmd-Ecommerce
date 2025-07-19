<?php

namespace App\Controller\Account;

use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\AddressUserType;
use App\Entity\Address;

final class AddressController extends AbstractController
{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        // Constructor can be used for dependency injection if needed
        $this->em = $em;
    }   
       
    #[Route('/compte/adresses', name: 'app_account_addresses')]
    public function index(): Response
    {
        // Render the addresses in a Twig template
        return $this->render('account/address/index.html.twig');
    }
    
    #[Route('/compte/adresses/delete/{id}', name: 'app_account_address_delete')]
    public function delete($id, AddressRepository $addressRepository): Response
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
    public function form(Request $request, $id, AddressRepository $addressRepository): Response
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
        
        return $this->render('account/address/form.html.twig', [
           'addressForm' => $from
        ]);
    }
}