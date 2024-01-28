<?php
// src/Controller/ProductController.php

namespace App\Controller;

use App\Service\UserInfoService;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct(UserInfoService $userInfoService) {
        $this->userInfoService = $userInfoService;
    }
    private $userInfoService;
    #[Route('/product/{id}', name: 'product')]
    public function show(Product $product): Response
    {
        $userInfo = $this->userInfoService->getUserInfo();
        return $this->render('product.html.twig', [
            'product' => $product,'userInfo' => $userInfo
        ]);
    }
}
