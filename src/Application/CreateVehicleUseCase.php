<?php

namespace App\Application;

use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;

class CreateVehicleUseCase
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,

    ) {
        $this->entityManager = $entityManager;
    }

    public function execute(string $model, string $brand, float $pricePerDay)
    {
        try {
            $vehicle = new Vehicle($model, $brand, $pricePerDay);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }


        try {
            $this->entityManager->persist($vehicle);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            throw new \Exception("Cannot create vehicle. Please try again later");
        }
    }
}
