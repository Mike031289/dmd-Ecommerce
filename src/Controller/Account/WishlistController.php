<?php
namespace App\Controller\Account;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;

final class WishlistController extends AbstractController
{
    #[Route('/compte/liste-de-souhaits', name: 'app_account_wishlist')]
    public function index(): Response
    {
        return $this->render('account/wishlist/index.html.twig');
    }

    #[Route('/compte/liste-de-souhaits/add/{id}', name: 'app_account_wishlist_add')]
    public function add($id, ProductRepository $productRepository, EntityManagerInterface $em, Request $request): Response
    {
        //find the product by id
        $product = $productRepository->findOneById($id);

        //add the product to the wishlist if not already in it
        if ($this->getUser()->getWishlists()->contains($product)) {
                
            $this->addFlash(
                    'info',
                    "Cet article est déjà dans votre liste de souhaits, veuillez consulter votre liste de souhaits en cliquant sur le coeur en haut à droite de la page."
                );
            
            return $this->redirect($request->headers->get('referer'));
            
        } else if ($product) {
            
            $this->getUser()->addWishlist($product);

            //flush the changes to the database
            $em->flush();

            $this->addFlash(
                'success',
                "Article correctement ajouté à votre liste de souhaits"
            );

            return $this->redirectToRoute(('app_account_wishlist'));
            
        } else if (!$product) {
            $this->addFlash(
                'error',
                "Le produit que vous essayez d'ajouter n'existe pas"
            );
        
            return $this->redirect($request->headers->get('referer'));
        
        }

        // Fallback return to satisfy static analyzers: redirect to wishlist if no branch returned
        return $this->redirectToRoute('app_account_wishlist');
    }

    #[Route('/compte/liste-de-souhaits/remove/{id}', name: 'app_account_wishlist_remove')]
    public function remove($id, ProductRepository $productRepository, EntityManagerInterface $em): Response
    {
        //find the product by id
        $product = $productRepository->findOneById($id);

        //remove the product from the wishlist
        if ($product) {
            $this->getUser()->removeWishlist($product);

            //flush the changes to the database
            $em->flush();

            $this->addFlash(
                'success',
                "Article correctement supprimé de votre liste de souhaits"
            );
        }

        return $this->redirectToRoute(('app_account_wishlist'));
    }
}