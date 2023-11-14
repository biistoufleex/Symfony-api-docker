<?php

namespace App\Service\Application;

use App\Entity\Application\DepotMr005Validation;
use App\Mapper\Application\DepotMr005ValidationMapper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;

class DepotMr005ValidationService
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;
    private DepotMr005ValidationMapper $depotMr005ValidationMapper;


    public function __construct
    (
        LoggerInterface            $logger,
        EntityManagerInterface $entityManager,
        DepotMr005ValidationMapper $depotMr005ValidationMapper
    )
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->depotMr005ValidationMapper = $depotMr005ValidationMapper;
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

    public function saveFormData(array $formData, array $fileData): ?DepotMr005Validation
    {
        $depotMr005Validation = null;
        try {
            $depotMr005Validation = $this->depotMr005ValidationMapper->map(
                $formData['depot_mr005'],
                $fileData['depot_mr005']['fileType']
            );
            $this->save($depotMr005Validation);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $depotMr005Validation;
    }
}