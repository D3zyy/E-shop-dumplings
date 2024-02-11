<?php
// src/Controller/IndexController.php

namespace App\Controller;

use App\Entity\Product;
use App\Service\UserInfoService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private $userInfoService;
    private $entityManager;

    public function __construct(UserInfoService $userInfoService, EntityManagerInterface $entityManager)
    {
        $this->userInfoService = $userInfoService;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $userInfo = $this->userInfoService->getUserInfo();
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return $this->render('index.html.twig', ['userInfo' => $userInfo, 'Products' => $products]);
    }
    #[Route('/about-us', name: 'about-us')]
    public function about_us(): Response
    {
        $userInfo = $this->userInfoService->getUserInfo();


        return $this->render('about-us.html.twig', ['userInfo' => $userInfo]);
    }

    #[Route('/questions', name: 'questions')]
    public function questions(): Response
    {
        $userInfo = $this->userInfoService->getUserInfo();
        

        return $this->render('questions.html.twig', ['userInfo' => $userInfo]);
    }
}
