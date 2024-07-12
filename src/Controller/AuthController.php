<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthController extends AbstractController
{
    public function __construct(
        private readonly JWTTokenManagerInterface $jwtManager,
    ) {
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(UserInterface $user): JsonResponse
    {
        // Generate a JWT token for the authenticated user
        $token = $this->jwtManager->create($user);

        return new JsonResponse(['token' => $token]);
    }
}
