<?php

namespace App\Controller;

use App\Application\CreateUserUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{

    private $createUserUseCase;

    public function __construct(CreateUserUseCase $createUserUseCase)
    {
        $this->createUserUseCase = $createUserUseCase;
    }

    #[Route('/user/create', name: 'user_create', methods: ['POST'])]
    public function createUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['error' => 'Invalid JSON'], 400);
        }

        try {
            $this->createUserUseCase->execute(
                $data['firstName'],
                $data['lastName'],
                $data['email'],
                new \DateTimeImmutable($data['licenseObtainedAt']),
                $data['password']
            );

            return new JsonResponse(['message' => 'User created successfully'], 201);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred while creating the user'], 500);
        }
    }
}
