<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column]
    private ?bool $hasInsurance = null;

    #[ORM\Column(length: 20)]
    private ?string $paymentMode = null;

    #[ORM\Column]
    private ?float $totalPrice = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $customer = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    private ?Vehicle $vehicle = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $paidAt = null;

    public function __construct(
        \DateTimeInterface $startDate,
        \DateTimeInterface $endDate,
        bool $hasInsurance = false,
        string $paymentMode,
        User $customer,
        Vehicle $vehicle
    ) {
        $today = new \DateTimeImmutable('today');

        if ($startDate < $today || $endDate < $today) {
            throw new \InvalidArgumentException('Start and end dates must be after today.');
        }

        if ($startDate >= $endDate) {
            throw new \InvalidArgumentException('Start date must be before end date.');
        }

        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->hasInsurance = $hasInsurance;
        $this->paymentMode = $paymentMode;
        $this->customer = $customer;
        $this->vehicle = $vehicle;
        $this->status = 'CART';
        $this->totalPrice = $this->bookingPrice();
    }

    public function bookingPrice(): float
    {
        $price = $this->vehicle->getPricePerDay() * $this->startDate->diff($this->endDate)->days;

        if ($this->hasInsurance) {
            $price += 20;
        }

        return $price;
    }

    public function updateInsuranceBooking(bool $hasInsurance)
    {
        $this->hasInsurance = $hasInsurance;
    }

    public function payBooking()
    {

        if ($this->status !== "CART") {
            throw new \Exception("Invoice cannot be paid");
        }

        $this->status = "PAID";
        $this->paidAt = new \DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function hasInsurance(): ?bool
    {
        return $this->hasInsurance;
    }

    public function setHasInsurance(bool $hasInsurance): static
    {
        $this->hasInsurance = $hasInsurance;

        return $this;
    }

    public function getPaymentMode(): ?string
    {
        return $this->paymentMode;
    }

    public function setPaymentMode(string $paymentMode): static
    {
        $this->paymentMode = $paymentMode;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): static
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPaidAt(): ?\DateTimeImmutable
    {
        return $this->paidAt;
    }

    public function setPaidAt(?\DateTimeImmutable $paidAt): static
    {
        $this->paidAt = $paidAt;

        return $this;
    }
}
