<?php

namespace App\Application;

use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteVehicleUseCase
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

    public function execute(int $id)
    {
        $vehicle = $this->vehicleRepository->find($id);

        if (is_null($vehicle)) {
            throw new \Exception("Vehicule not found");
        }

        try {
            $this->entityManager->remove($vehicle);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            throw new \Exception("Failed to delete vehicle. Please try again later");
        }
    }
}
