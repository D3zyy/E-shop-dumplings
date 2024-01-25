<?php
// src/Controller/IndexController.php

namespace App\Controller;

use App\Service\UserInfoService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController {
    private $userInfoService;

    public function __construct(UserInfoService $userInfoService) {
        $this->userInfoService = $userInfoService;
    }

    #[Route('/', name: 'index')]
    public function index(): Response {
        $userInfo = $this->userInfoService->getUserInfo();

        return $this->render('index.html.twig', $userInfo);
    }
}
