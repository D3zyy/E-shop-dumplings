<?php
// src/Service/UserInfoService.php

namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;

class UserInfoService {
    private $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    public function getUserInfo() {
        $user = $this->security->getUser();
        $isAdmin = $this->security->isGranted('ROLE_ADMIN');
        $isLoggedIn = $this->security->isGranted('IS_AUTHENTICATED_FULLY');

        return [
            'user' => $user,
            'isAdmin' => $isAdmin,
            'isLoggedIn' => $isLoggedIn,
        ];
    }
}
