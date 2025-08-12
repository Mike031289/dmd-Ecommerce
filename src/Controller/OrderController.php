<?php

namespace App\Controller;

use App\Class\Cart;
use App\Entity\User;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        
        // If the user has no addresses, redirect to the address creation page
        if (count($addresses) === 0) {
            $this->addFlash('warning', 'Vous devez ajouter une adresse de livraison avant de passer une commande.');
            // Redirect to the address form page
            return $this->redirectToRoute('app_account_address_form');
        }
        
        // Create the order form and pass the user's addresses as a form option
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses,
            'action' => $this->generateUrl('app_order_summary'),
        ]);

        // Render the order page template with the form view
        return $this->render('order/index.html.twig', [
            'deliveryForm' => $form->createView(),
        ]);
    }
    

    #[Route('/commande/recapitulatif', name: 'app_order_summary')]
    public function add(Request $request, Cart $cart): ? Response
    {
        /** @var User $user The currently authenticated user (loaded from the session) */
        $user = $this->getUser();   
        $addresses = $user->getAddresses();
        
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $user->getAddresses(),   
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Here you would typically handle the order summary logic, such as displaying the cart contents
            // and allowing the user to confirm their order.
            
            // For now, we will just render the summary page.
            return $this->render('order/summary.html.twig', [
                'choices' => $form->getData(),
                'cart' => $cart->getCart(),
                
            ]);
        }
    }
}