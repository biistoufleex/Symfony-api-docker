<?php

namespace App\Repository;

use App\Entity\OrganisationAutorisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganisationAutorisation>
 *
 * @method OrganisationAutorisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganisationAutorisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganisationAutorisation[]    findAll()
 * @method OrganisationAutorisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationAutorisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganisationAutorisation::class);
    }

    public function findActiveOrganisations($identifiant)
    {
        $currentDate = new \DateTime(); // Get the current date and time

        return $this->createQueryBuilder('oa')
            ->andWhere('oa.identifiantOrganisationPlage = :identifiant')
            ->andWhere('oa.dateDebut <= :currentDate')
            ->andWhere('oa.dateFin IS NULL OR oa.dateFin >= :currentDate')
            ->setParameter('identifiant', $identifiant)
            ->setParameter('currentDate', $currentDate)
            ->getQuery()
            ->getResult();
    }
}
