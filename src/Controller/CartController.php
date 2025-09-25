<?php

namespace App\Controller;

use App\Class\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{

   #[Route('/mon-panier', name: 'app_cart')]
   public function index(Cart $cart, Request $request): Response
   {
        if (count($cart->getCart()) === 0) {
            $this->addFlash('warning', 'Votre panier est vide. Veuillez ajouter des articles avant de passer une commande.');
            return $this->redirect($request->headers->get('referer'));
        }
    
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart(),
            'totalWt' => $cart->getTotalWt(),
        ]);
   }

   #[Route('/cart/add/{id}', name: 'app_cart_add')]
   public function addProduct($id, Cart $cart, ProductRepository $productRepository, Request $request): Response
   {
      $product = $productRepository->findOneById($id);

      $cart->add($product);

      $this->addFlash(
         'success',
         "Article correctement ajouté à votre panier "
      );
      return $this->redirect($request->headers->get('referer'));
   }
   
   #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
   public function decreaseProduct($id, Cart $cart): Response
   {
      $cart->decrease($id);

      $this->addFlash(
         'success',
         "Article supprimé de votre panier "
      );
      return $this->redirectToRoute('app_cart');
   }
   
   #[Route('/cart/remove', name: 'app_cart_remove')]
   public function removeProduct(Cart $cart): Response
   {
      $cart->removeAll();
      
      return $this->redirectToRoute('app_home');
   }
}