<?php

namespace App\Controller;

use App\Application\CreateVehicleUseCase;
use App\Application\DeleteVehicleUseCase;
use App\Application\UpdateVehicleUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class VehicleController extends AbstractController
{
    private $createVehicleUseCase;
    private $updateVehicleUseCase;
    private $deleteVehicleUseCase;
    public function __construct(CreateVehicleUseCase $createVehicleUseCase, UpdateVehicleUseCase $updateVehicleUseCase, DeleteVehicleUseCase $deleteVehicleUseCase)
    {
        $this->createVehicleUseCase = $createVehicleUseCase;
        $this->updateVehicleUseCase = $updateVehicleUseCase;
        $this->deleteVehicleUseCase = $deleteVehicleUseCase;
    }

    #[Route('/vehicle/create', name: 'vehicle_create', methods: ['POST'])]
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

    #[Route('/vehicle/update/{id}', name: 'vehicle_update', methods: ['POST'])]
    public function updateVehicle(int $id, Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['error' => 'Invalid JSON'], 400);
        }

        try {
            $this->updateVehicleUseCase->execute(
                $id,
                $data['model'],
                $data['brand'],
                $data['pricePerDay']
            );

            return new JsonResponse(['message' => 'Vehicle updated successfully'], 200);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred while updating the vehicle'], 500);
        }
    }

    #[Route('/vehicle/delete/{id}', name: 'vehicle_delete', methods: ['POST'])]
    public function deleteVehicle(int $id): JsonResponse
    {
        try {
            $this->deleteVehicleUseCase->execute($id);
            return new JsonResponse(['message' => 'Vehicle deleted successfully'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred while deleting the vehicle'], 500);
        }
    }
}
