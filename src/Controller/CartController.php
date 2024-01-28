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
        // Získání existujícího košíku ze session
        $cart = $session->get('cart', []);

        // Získání hodnoty množství z formuláře nebo z query parametru
        $quantity = $request->request->get('quantity', $request->query->get('quantity'));
        $quantity = max(intval($quantity), 1);
        // Přidání nové položky do košíku
        $product = ['id' => $id, 'quantity' => $quantity];

        $existingProductKey = null;
        foreach ($cart as $key => $cartItem) {
            if ($cartItem['id'] === $id) {
                $existingProductKey = $key;
                break;
            }
        }

        // Pokud produkt již existuje v košíku, upravíme pouze jeho kvantitu
        if ($existingProductKey !== null) {
            $cart[$existingProductKey]['quantity'] += $quantity;
        } else {
            // Jinak přidáme novou položku do košíku
            $product = ['id' => $id, 'quantity' => $quantity];
            $cart[] = $product;
        }

        // Uložení aktualizovaného košíku zpět do session
        $session->set('cart', $cart);

        // Redirect zpět na stránku s produktem nebo kamkoliv jinam
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart', name: 'cart')]
    public function showCart(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        // Inicializace prázdného pole pro produkty v košíku
        $productsInCart = [];

        // Výpočet celkové ceny
        $finalPrice = 0;

        // Projdeme všechny položky v košíku
        foreach ($cart as $cartItem) {
            // Získáme ID a množství položky v košíku
            $productId = $cartItem['id'];
            $quantity = $cartItem['quantity'];

            // Načteme produkt z databáze podle ID
            $product = $this->productService->getProductById($productId);

            // Pokud produkt existuje, přidáme ho do pole
            if ($product) {
                $productsInCart[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $quantity * $product->getPrice(), // Přístup přímo k atributu price
                ];

                // Přičteme cena produktu * množství k celkové ceně
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
