<?php

namespace App\Service\Application;

use App\Entity\Application\DepotMr005;
use App\Entity\Application\DepotMr005Formulaire;
use App\Mapper\Application\DepotMr005Mapper;
use App\Repository\Application\DepotMr005Repository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;

class DepotMr005Service
{
    private LoggerInterface $logger;
    private DepotMr005Repository $depotMr005Repository;
    private EntitymanagerInterface $entityManager;
    private DepotMr005Mapper $depotMr005Mapper;

    public function __construct(
        LoggerInterface $logger,
        DepotMr005Repository   $depotMr005Repository,
        EntitymanagerInterface $entityManager,
        DepotMr005Mapper       $depotMr005Mapper
    ) {
        $this->logger = $logger;
        $this->depotMr005Repository = $depotMr005Repository;
        $this->entityManager = $entityManager;
        $this->depotMr005Mapper = $depotMr005Mapper;
    }

    /**
     * @param string $ipe
     * @return array<DepotMr005>|null
     */
    public function getRecepiceByIpe(string $ipe) : ?array
    {
        $this->logger->info('getRecepiceByIpe');
        return $this->depotMr005Repository->findBy(['ipe' => $ipe]);
    }

    public function getOneRecepiceByIpe(string $ipe) : ?DepotMr005
    {
        $this->logger->info('getOneRecepiceByIpe');
        return $this->depotMr005Repository->findOneBy(['ipe' => $ipe]);
    }

    /**
     * @param string $finess
     * @return array<DepotMr005>|null
     */
    public function getRecepiceByFiness(string $finess) : ?array
    {
        $this->logger->info('getRecepiceByFiness');
        return $this->depotMr005Repository->findBy(['finess' => $finess]);
    }

    public function save(DepotMr005 $depotMr005): void
    {
        $this->entityManager->persist($depotMr005);
        $this->entityManager->flush();
    }

    public function saveDepot(DepotMr005Formulaire $depotMr005Formulaire, string $idPlage): void
    {
        try {
            $depot = $this->depotMr005Mapper->mapFormDepot($depotMr005Formulaire, $idPlage);
            $this->save($depot);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}