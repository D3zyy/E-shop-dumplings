<?php
// src/Controller/CartController.php

namespace App\Controller;

use App\Entity\Product;
use App\Service\UserInfoService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    private $userInfoService;

    public function __construct(UserInfoService $userInfoService)
    {
        $this->userInfoService = $userInfoService;

    }


    #[Route('/add-to-cart/{id}', name: 'add_to_cart')]
    public function addToCart(int $id): Response
    {
        $cart = $this->get('session')->get('cart', []);

        // Add the product ID to the cart
        $cart[] = $id;

        // Save the updated cart in the session
        $this->get('session')->set('cart', $cart);

        // Redirect back to the product page or wherever you prefer
        return $this->redirectToRoute('product_show', ['id' => $id]);
    }

    #[Route('/cart', name: 'cart')]
    public function showCart(): Response
    {
        $cart = $this->get('session')->get('cart', []);

        // Fetch products based on the product IDs in the cart (this logic may vary based on your actual implementation)
        // For demonstration purposes, we're just using a dummy array of products
        $productsInCart = [];

        foreach ($cart as $productId) {
            // Fetch the actual product entity based on the ID
            // You would need to implement this logic based on your entity structure and database
            $product = $this->getDoctrine()->getRepository(Product::class)->find($productId);

            if ($product) {
                $productsInCart[] = $product;
            }
        }

        return $this->render('cart/cart.html.twig', [
            'productsInCart' => $productsInCart,
        ]);
    }
    #[Route('/complete-purchase', name: 'complete_purchase')]
    public function completePurchase(): Response
    {
        // For demonstration purposes, let's simulate completing the purchase by clearing the cart
        $this->get('session')->set('cart', []);

        // Display an alert message (you might want to use JavaScript for a more dynamic alert)
        $this->addFlash('success', 'Purchase completed!');

        // Redirect back to the cart or wherever you prefer
        return $this->redirectToRoute('cart');
    }
}
