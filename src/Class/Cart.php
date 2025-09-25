<?php

namespace App\Class;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{

	public function __construct(private RequestStack $requestStack)
	{

	}

    /**
     * Summary of add
     * @param mixed $product
     * @return void
     * This function is used to add product in a cart (shoping bag)
     */
	public function add($product)
	{
		// Here we call session to get cart contente available in the session
		$cart = $this->getCart();

		$productId = $product->getId();

		//Ajouter une qté + 1 au produit
		if (isset($cart[$productId])) {
			$cart[$productId]['qty'] += 1;
		} else {
			$cart[$productId] = [
				'object' => $product,
				'qty'    => 1
			];
		}

		$this->requestStack->getSession()->set('cart', $cart);
	}
    
    /**
     * Summary of decrease
     * @param mixed $id
     * @return void
     * This function is used to delete or decrease product quantity in a cart (shoping bag)
     */
    public function decrease($id)
    {
        // Here we call session to get cart contente available in the session
		$cart = $this->getCart();

        if($cart[$id]['qty'] > 1){
            $cart[$id]['qty'] = $cart[$id]['qty'] - 1;
        } else {
            unset($cart[$id]);
        }
        
		$this->requestStack->getSession()->set('cart', $cart);
    }
    
    /**
     * Summary of fullQuantity
     * @return float|int
     * This function is used to get total quantity of the product in a cart (shoping bag)
     */
    public function fullQuantity()
    {
        // appeler la session symfony
		$cart = $this->getCart();
        $quantity = 0;
        if (!isset($cart)) {
            return $quantity;
        }
        foreach ($cart as $product){
            $quantity = $quantity + $product['qty'];
        }
        
        return $quantity;
    }

    /**
     * Summary of getTotalWt
     * @return float|int
     * This function is used to get total price of all product in a cart (shoping bag)
     */
    public function getTotalWt()
    {
          // appeler la session symfony
		$cart = $this->getCart();
        $price = 0;
        
        if (!isset($cart)) {
            return $price;
        }
        foreach ($cart as $product){
            $price += $product['object']->_getPriceWt() * $product['qty'];
        }
        
        return $price;
        
    }

    /**
     * Summary of removeAll
     * This function is used to delete all product in a cart (shoping bag)
     */
    public function removeAll()
    {
        return $this->requestStack->getSession()->remove('cart');
    }
    
    /**
     * Summary of getCart
     * This function is used to get all product in a cart (shoping bag), coming from Session
     */
	public function getCart()
	{
		return $this->requestStack->getSession()->get('cart', []);
	}
}