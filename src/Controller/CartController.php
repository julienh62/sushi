<?php

namespace App\Controller;

use App\Entity\Product;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function cart( RequestStack $request, ProductRepository $productRepository , SessionInterface $session )
    {
        //$session = $request->getSession();
        //dd($session);
        $cart = $session->get("cart", []);
       
       //  dd($cart);
        $products = [];

        foreach ($cart as $id => $quantity) {
            $product = $productRepository->find($id);
            $product->setQuantity($quantity);
            $products[]= $product;   
        }
         //dd($products);



        return $this->render('cart/index.html.twig', [
            'products' => $products,
          
            
        ]);
    }

    #[Route('/add-cart/{product}', name: 'add-cart')] 
    public function Addcart( Product $product , Request $request, SessionInterface $session )
    {
        if (!$product) {
            return $this->redirectToRoute("home");
        }
        $quantity = $request->get("quantity");
        

        $cart = $session->get("cart", []);
        //on pushe product dans cart
        $cart[$product->getId()] = $quantity;

        $session->set("cart", $cart);

        
       //  dd($cart);
      /*  return $this->render('cart/cart.html.twig', [
           'cart' => $cart,
           'quantity' => $quantity,
           ''
        
        ]); */
        return $this->redirectToRoute("product_index");
    }
    

    #[Route('/cart/update{product}', name: 'app_cart_update')] 
    public function update( Product $product , Request $request, SessionInterface $session )
    {
        $cart = $session->get("cart", []);
        $quantity = $request->get("quantity");
        $cart[$product->getId()] = $quantity;
        $session->set("cart", $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove{product}', name: 'app_cart_remove')] 
    public function remove( Product $product , Request $request, SessionInterface $session )
    {
        $cart = $session->get("cart", []);
        if (array_key_exists($product, $cart)) {
            unset($cart[$product]);
            $session->set('cart' , $cart);
        }

        return $this->redirectToRoute('app_cart');
    }

    
    #[Route('/cart-size', name: 'cart_size')] 
    public function cartSize(  SessionInterface $session )
    {   
        $totalQuantity = 0;
        $cart = $session->get("cart", []);

        foreach ( $cart as $id =>$quantity) {
            if($quantity>0) {
                $totalQuantity+=$quantity;
            }
            
        }

        return new Response($totalQuantity);
    }
    
}