<?php

namespace App\Application;

use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;

class PayBookingUseCase
{
    private BookingRepository $bookingRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        BookingRepository $bookingRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->entityManager = $entityManager;
    }

    public function execute(int $id)
    {
        $booking = $this->bookingRepository->find($id);

        if (is_null($booking)) {
            throw new \Exception("Booking not found");
        }

        try {
            $booking->payBooking();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        try {
            $this->entityManager->persist($booking);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception("Could not pay booking.");
        }
    }
}
