<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Service\UserInfoService;
class SecurityController extends AbstractController
{
    public function __construct(UserInfoService $userInfoService) {
        $this->userInfoService = $userInfoService;
    }
    private $userInfoService;

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        

        if ($this->getUser()) {
            return $this->redirectToRoute('main_page');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $userInfo = $this->userInfoService->getUserInfo();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error,'userInfo' => $userInfo]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
