<?php

namespace App\Repository;

use App\Entity\HabilitationsOrganisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HabilitationsOrganisation>
 *
 * @method HabilitationsOrganisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method HabilitationsOrganisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method HabilitationsOrganisation[]    findAll()
 * @method HabilitationsOrganisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HabilitationsOrganisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HabilitationsOrganisation::class);
    }

//    /**
//     * @return HabilitationsOrganisation[] Returns an array of HabilitationsOrganisation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HabilitationsOrganisation
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
