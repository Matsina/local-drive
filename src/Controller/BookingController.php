<?php

namespace App\Controller;

use App\Application\CreateBookingUseCase;
use App\Repository\UserRepository;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class BookingController extends AbstractController
{
    private $createBookingUseCase;

    public function __construct(
        CreateBookingUseCase $createBookingUseCase,
    ) {
        $this->createBookingUseCase = $createBookingUseCase;
    }
    #[Route('/booking/create', name: 'booking_create', methods: ['POST'])]
    public function createBooking(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        if (!$data) {
            return new JsonResponse(['error' => 'Invalid JSON'], 400);
        }
        try {
            $startDate = new \DateTime($data['startDate']);
            $endDate = new \DateTime($data['endDate']);
            $hasInsurance = filter_var($data['hasInsurance'], FILTER_VALIDATE_BOOLEAN);
            $paymentMode = $data['paymentMode'];

            $this->createBookingUseCase->execute(
                $startDate,
                $endDate,
                $hasInsurance,
                $paymentMode,
                $user,
                $vehicle
            );

            return new JsonResponse(['message' => 'Booking created successfully'], 201);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred while creating the booking'], 500);
        }
    }
}
