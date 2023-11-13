<?php

namespace App\Service\Application;

use App\Entity\Application\DepotMr005Validation;
use Doctrine\ORM\EntityManagerInterface;

class DepotMr005ValidationService
{
    private EntityManagerInterface $entityManager;

    public function __construct
    (
        EntityManagerInterface $entityManager,
    )
    {
        $this->entityManager = $entityManager;
    }

    public function save(DepotMr005Validation $depotMr005Validation): void
    {
        $this->entityManager->persist($depotMr005Validation);
        $this->entityManager->flush();
    }

    public function getDepotMr005ValidationByRecepice(string $recepice): ?DepotMr005Validation
    {
        return $this->entityManager->getRepository(DepotMr005Validation::class)->findOneBy(['numeroRecepice' => $recepice]);
    }

    public function existByRecepice(string $recepice): bool
    {
        return (bool)$this->entityManager->getRepository(DepotMr005Validation::class)->findOneBy(['numeroRecepice' => $recepice]);
    }
}