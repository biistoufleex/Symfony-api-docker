<?php

namespace App\Repository\Application;

use App\Entity\Application\DepotMr005Formulaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DepotMr005Formulaire>
 *
 * @method DepotMr005Formulaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method DepotMr005Formulaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method DepotMr005Formulaire[]    findAll()
 * @method DepotMr005Formulaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepotMr005FormulaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DepotMr005Formulaire::class);
    }
}
