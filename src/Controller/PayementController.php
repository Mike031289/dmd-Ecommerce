<?php

namespace App\Controller;

use App\Class\Cart;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PayementController extends AbstractController
{
    #[Route('/commande/paiement/{id_order}', name: 'app_payement')]
    public function index(int $id_order, OrderRepository $orderRepository, EntityManagerInterface $em): Response
    {
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']); // pour la Bonne pratique : utiliser .env Replace with your actual secret key 
        
        $YOUR_DOMAIN = 'https://127.0.0.1:8000/';

        /** @var Order|null $order */
        $order = $orderRepository->findOneBy([
            'id' => $id_order,
            'user' => $this->getUser()
        ]);

        if (! $order || $order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $product_for_stripe = [];

        /** @var \App\Entity\OrderDetail $product */
        foreach ($order->getOrderDetails() as $product) {
            // Products in the cart
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => number_format($product->getProductPriceWt() * 100, 0, '', ''),
                    'product_data' => [
                        'name' => $product->getProductName(),
                        'images' => [$_ENV['DOMAIN_NAME'].'/uploads/'.$product->getProductIllustration()]
                    ],
                ],
                'quantity' => $product->getProductQuantity(),
            ];
        }
        
        // Adding the carrier price as a product
        $product_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => number_format($order->getCarrierPrice() * 100, 0, '', ''),
                'product_data' => [
                    'name' => 'Transporteur : ' . $order->getCarrierName(),
                ],
            ],
            'quantity' => 1,
        ];
        
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'line_items' => [[
                $product_for_stripe
                ]],
            'mode' => 'payment',
            'success_url' => $_ENV['DOMAIN_NAME'] . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV['DOMAIN_NAME'] . '/mon-panier/annulation',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $em->flush();
        
        return $this->redirect($checkout_session->url);
    }

    #[Route('/commande/merci/{stripe_session_id}', name: 'app_payement_success')]
    public function success(string $stripe_session_id, OrderRepository $orderRepository, EntityManagerInterface $em, Cart $cart): Response
    {
        $order = $orderRepository->findOneBy([
            'stripe_session_id' => $stripe_session_id,
            'user' => $this->getUser()
        ]);

        if (!$order) {
            return $this->redirectToRoute('app_home');
        }
        
        if ($order->getState() == 0) {
            // we set the order as paid
            $order->setState(1);
            // we empty the cart
            $cart->removeAll();
            $em->flush();
        }

        return $this->render('payement/success.html.twig', [
            'order' => $order
        ]);
        
        
    }
}