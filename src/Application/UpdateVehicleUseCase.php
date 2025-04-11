<?php

namespace App\Application;

use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateVehicleUseCase
{
    private VehicleRepository $vehicleRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        VehicleRepository $vehicleRepository,
        EntityManagerInterface $entityManager

    ) {
        $this->vehicleRepository = $vehicleRepository;
        $this->entityManager = $entityManager;
    }

    public function execute(int $id, string $model, string $brand, float $pricePerDay)
    {
        $vehicle = $this->vehicleRepository->find($id);

        if (is_null($vehicle)) {
            throw new \Exception("Vehicule not found");
        }

        try {
            $vehicle->updateVehicle($model, $brand, $pricePerDay);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        try {
            $this->entityManager->persist($vehicle);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            throw new \Exception("Cannot update vehicle. Please try again later");
        }
    }
}
