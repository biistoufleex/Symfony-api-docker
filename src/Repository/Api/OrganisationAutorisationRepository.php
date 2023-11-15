<?php

namespace App\Repository\Api;

use App\Entity\Api\OrganisationAutorisation;
use DateTime;
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
    /**
     * Find active organizations based on the provided identifier.
     *
     * This method queries the database to find active organizations associated with the provided identifier.
     * It checks for organizations where the start date is less than or equal to the current date and time and
     * where the end date is either null or greater than or equal to the current date and time.
     *
     * @param string $identifiant The identifier for which to find active organizations.
     *
     * @return array<string, mixed> An array of active organizations found in the database.
     */
    public function findActiveOrganisations(string $identifiant): array
    {
        $currentDate = new DateTime(); // Get the current date and time

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
