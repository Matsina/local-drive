<?php

namespace App\Application;

use App\Entity\Booking;
use App\Entity\User;
use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;

class CreateBookingUseCase
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,

    ) {
        $this->entityManager = $entityManager;
    }

    public function execute(\DateTimeInterface $startDate, \DateTimeInterface $endDate, bool $hasInsurance, string $paymentMode, User $customer, Vehicle $vehicle)
    {
        try {
            $booking = new Booking($startDate, $endDate, $hasInsurance, $paymentMode, $customer, $vehicle);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
        try {
            $this->entityManager->persist($booking);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            throw new \Exception("Cannot create booking. Please try again later");
        }
    }
}
