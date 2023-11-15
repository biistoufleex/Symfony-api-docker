<?php

namespace App\Repository\Application;

use App\Entity\Application\DepotMr005;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DepotMr005>
 *
 * @method DepotMr005|null find($id, $lockMode = null, $lockVersion = null)
 * @method DepotMr005|null findOneBy(array $criteria, array $orderBy = null)
 * @method DepotMr005[]    findAll()
 * @method DepotMr005[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepotMr005Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DepotMr005::class);
    }
}
