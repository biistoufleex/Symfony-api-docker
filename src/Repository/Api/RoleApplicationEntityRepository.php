<?php

namespace App\Repository\Api;

use App\Entity\Api\RoleApplication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoleApplication>
 *
 * @method RoleApplication|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoleApplication|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoleApplication[]    findAll()
 * @method RoleApplication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleApplicationEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoleApplication::class);
    }

}
