<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(AuthenticationUtils $authenticationUtils): JsonResponse
    {
        // Récupération de l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupération du dernier identifiant saisi par l'utilisateur
        // $lastUsername = $authenticationUtils->getLastUsername();

        if ($error) {
            return $this->json([
                'error' => $error->getMessageKey(),
            ], 401);
        }

        $user = $this->getUser();

        return $this->json([
            'message' => 'Connexion réussie',
            'user' => $user->getUserIdentifier(),
        ]);
    }


    #[Route('/logout', name: 'app_logout')]
    public function logout(): void {}
}
