<?php

namespace App\Controller;

use App\Service\UserInfoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $userInfoService;
    public function __construct(UserInfoService $userInfoService) {
        $this->userInfoService = $userInfoService;
    }


    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        $userInfo = $this->userInfoService->getUserInfo();
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $user = $userInfo['user'];
        $isAdmin = $userInfo['isAdmin'];
        $isLoggedIn = $userInfo['isLoggedIn'];
        return $this->render('login.html.twig', ['last_username' => $lastUsername, 'error' => $error,'user' => $user,'isAdmin' => $isAdmin, 'isLoggedIn' => $isLoggedIn]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
