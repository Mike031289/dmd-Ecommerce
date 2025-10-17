<?php

namespace App\Controller;

use App\Class\Cart;
use App\Entity\User;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderDetail;
use Doctrine\ORM\EntityManagerInterface;
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
    public function add(Request $request, Cart $cart, EntityManagerInterface $em): ? Response
    {
        // We retrieve the full cart details so we can use it in the template avery where needed
        $products = $cart->getCart();
        // We check if the user has submitted the form with POST method, if not we redirect them to the cart page
        if ($request->getMethod() !== 'POST') {
            return $this->redirectToRoute('app_cart');
        }
        
        /** @var User $user The currently authenticated user (loaded from the session) */
        $user = $this->getUser();   
        
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $user->getAddresses(),   
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            //we need to retrive the delivery address and formate this as to one string  before we set it to the order entity
            $addressObject = $form->get('addresses')->getData();
            $address = $addressObject->getFirstname().' '.$addressObject->getLastname();
            $address .= '<br/>'.$addressObject->getAddress();
            $address .= '<br/>'.$addressObject->getPostal().' '.$addressObject->getCity();
            $address .= '<br/>'.$addressObject->getCountry();
            $address .= '<br/>'.$addressObject->getPhone();
            
            //let's create a new Order entity and set its properties and the formatted address we just created and save it to the database
            //we will need the entity manager to persist and flush the order entity
            $order = new Order(); 
            $order->setUser($user);
            $order->setCreatedAt(new \DateTime());
            $order->setState(0);
            $order->setCarrierName($form->get('carrier')->getData()->getName());
            $order->setCarrierPrice($form->get('carrier')->getData()->getPrice());
            $order->setDelivery($address);
            
            //here we will process the registred order in the database
            foreach ($products as $product) {
                // Process each product in the cart
                $orderDetail = new OrderDetail();
                $orderDetail->setProductName($product['object']->getName());
                $orderDetail->setproductIllustration($product['object']->getIllustration());
                $orderDetail->setProductPrice($product['object']->getPrice());
                $orderDetail->setproductTva($product['object']->getTva());
                $orderDetail->setproductQuantity($product['qty']);
                $order->addOrderDetail($orderDetail);
            }
            
            $em->persist($order);
            $em->flush();   
        }
        // For now, we will just render the summary page.
        return $this->render('order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart' => $products,
            'totalWt' => $cart->getTotalWt()
        ]);
    
    }
}