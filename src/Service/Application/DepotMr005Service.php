<?php

namespace App\Service\Application;

use App\Repository\Application\DepotMr005Repository;
use Psr\Log\LoggerInterface;

class DepotMr005Service
{
    private LoggerInterface $logger;

    private DepotMr005Repository $depotMr005Repository;

    public function __construct(
        LoggerInterface $logger,
        DepotMr005Repository $depotMr005Repository
    ) {
        $this->logger = $logger;
        $this->depotMr005Repository = $depotMr005Repository;
    }

    public function getRecepiceByIpe(string $ipe) : ?array
    {
        $this->logger->info('getRecepiceByIpe');
        return $this->depotMr005Repository->findBy(['ipe' => $ipe]);
    }

    public function getRecepiceByFiness(string $finess) : ?array
    {
        $this->logger->info('getRecepiceByFiness');
        return $this->depotMr005Repository->findBy(['finess' => $finess]);
    }

}