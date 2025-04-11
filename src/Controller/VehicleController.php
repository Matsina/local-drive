<?php

namespace App\Controller;

use App\Application\CreateVehicleUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class VehicleController extends AbstractController
{

    private $createVehicleUseCase;
    public function __construct(CreateVehicleUseCase $createVehicleUseCase)
    {
        $this->createVehicleUseCase = $createVehicleUseCase;
    }

    #[Route('/vehicle-create', name: 'vehicle_create', methods: ['POST'])]
    public function createVehicle(Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['error' => 'Invalid JSON'], 400);
        }

        try {
            $this->createVehicleUseCase->execute(
                $data['model'],
                $data['brand'],
                $data['pricePerDay']
            );

            return new JsonResponse(['message' => 'Vehicle created successfully'], 201);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred while creating the vehicle'], 500);
        }
    }
}
