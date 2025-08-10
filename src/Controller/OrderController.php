<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    /**
     * Displays the order page.
     * 
     * This method allows the user to choose:
     *  - the delivery address
     *  - the carrier (shipping method)
     * 
     * The selected data will be processed later in the checkout flow.
     */
    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {
        /** @var User $user The currently authenticated user (loaded from the session) */
        $user = $this->getUser();
        $addresses = $user->getAddresses();
        
        // If the user has no addresses, redirect to the address creation page before proceeding with the order
        if (count($addresses) === 0) {
            return $this->redirectToRoute('app_account_address_form');
        }
        
        // Create the order form and pass the user's addresses as a form option
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses,
        ]);

        // Render the order page template with the form view
        return $this->render('order/index.html.twig', [
            'deliveryForm' => $form->createView(),
        ]);
    }
}