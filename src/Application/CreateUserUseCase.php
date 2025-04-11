<?php

namespace App\Application;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserUseCase
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $hasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $hasher
    ) {
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
    }

    public function execute(string $firstName, string $lastName, string $email, DateTimeImmutable $licenseObtainedAt, string $password)
    {
        try {
            $user = new User($firstName, $lastName, $email, $licenseObtainedAt, $password, $this->hasher);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new \InvalidArgumentException('The email address is already in use.', 400);
        } catch (\Exception $e) {
            throw new \Exception('Cannot create user.');
        }
    }
}
