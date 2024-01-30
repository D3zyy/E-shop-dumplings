<?php
// src/Controller/CartController.php

namespace App\Controller;

use App\Entity\Product;
use App\Service\UserInfoService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\Request;
class CartController extends AbstractController
{

    private $userInfoService;
    private $productService;
    public function __construct(UserInfoService $userInfoService,ProductService $productService)
    {
        $this->userInfoService = $userInfoService;
        $this->productService = $productService;

    }


    #[Route('/add-to-cart/{id}', name: 'add_to_cart', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function addToCart(int $id, Request $request, SessionInterface $session): Response
    {
      
        $cart = $session->get('cart', []);

        
        $quantity = $request->request->get('quantity', $request->query->get('quantity'));
        $quantity = max(intval($quantity), 1);

        $product = ['id' => $id, 'quantity' => $quantity];

        $existingProductKey = null;
        foreach ($cart as $key => $cartItem) {
            if ($cartItem['id'] === $id) {
                $existingProductKey = $key;
                break;
            }
        }

       
        if ($existingProductKey !== null) {
            $cart[$existingProductKey]['quantity'] += $quantity;
        } else {
          
            $product = ['id' => $id, 'quantity' => $quantity];
            $cart[] = $product;
        }

     
        $session->set('cart', $cart);
        

        $this->addFlash('success', 'Produkt úspěšně přidán do košíku');


        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/cart', name: 'cart')]
    public function showCart(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

       
        $productsInCart = [];


        $finalPrice = 0;

        
        foreach ($cart as $cartItem) {
    
            $productId = $cartItem['id'];
            $quantity = $cartItem['quantity'];


            $product = $this->productService->getProductById($productId);


            if ($product) {
                $productsInCart[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $quantity * $product->getPrice(), 
                ];

          
                $finalPrice += $quantity * $product->getPrice();
            }
        }

        $userInfo = $this->userInfoService->getUserInfo();

        return $this->render('cart.html.twig', [
            'productsInCart' => $productsInCart,
            'userInfo' => $userInfo,
            'finalPrice' => $finalPrice,
        ]);
    }
    #[Route('/remove-item/{name}', name: 'remove_item')]
public function removeItem(string $name, SessionInterface $session, ProductService $productService): Response
{

    $idProduct = $productService->getProductIdByName($name);

    if ($idProduct !== null) {
        $cart = $session->get('cart', []);


        $indexToRemove = array_search($idProduct, array_column($cart, 'id'));

        if ($indexToRemove !== false) {
          
            array_splice($cart, $indexToRemove, 1);

            $session->set('cart', $cart);

            $this->addFlash('removeItem', 'Položka úspěšně odebrána!');
        } else {
            $this->addFlash('error', 'Item not found in the cart.'); 
        }
    } else {
        $this->addFlash('error', 'Product not found.'); 
    }

    return $this->redirectToRoute('cart');
}

    #[Route('/complete-purchase', name: 'complete_purchase')]
    public function completePurchase(SessionInterface $session): Response
    {

        $session->remove('cart');

    
        $this->addFlash('success', 'Nákup úspěšně dokončen!');

        return $this->redirectToRoute('cart');
    }
}
