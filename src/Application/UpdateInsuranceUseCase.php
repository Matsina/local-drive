<?php

namespace App\Application;

use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateInsuranceUseCase
{
    private EntityManagerInterface $entityManager;
    private BookingRepository $bookingRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        BookingRepository $bookingRepository,

    ) {
        $this->entityManager = $entityManager;
        $this->bookingRepository = $bookingRepository;
    }

    public function execute(int $id, bool $hasInsurance)
    {
        $booking = $this->bookingRepository->find($id);

        if (is_null($booking)) {
            throw new \Exception("Booking not found");
        }

        try {
            $booking->updateInsuranceBooking($hasInsurance);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        try {
            $this->entityManager->persist($booking);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            throw new \Exception("Cannot update insurance booking. Please try again later");
        }
    }
}
