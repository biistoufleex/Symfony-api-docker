<?php

namespace App\Repository\Application;

use App\Entity\Application\DepotMr005Validation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DepotMr005Validation>
 *
 * @method DepotMr005Validation|null find($id, $lockMode = null, $lockVersion = null)
 * @method DepotMr005Validation|null findOneBy(array $criteria, array $orderBy = null)
 * @method DepotMr005Validation[]    findAll()
 * @method DepotMr005Validation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepotMr005ValidationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DepotMr005Validation::class);
    }
}
