<?php

namespace App\Service;

use App\Entity\OrganisationAutorisation;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class OrganisationAutorisationService
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    /**
     * Get the authorizations and permissions for a specific organization.
     *
     * This method retrieves the authorizations and permissions associated with a particular organization
     * identified by its ID. It queries the database to find active organizations and their respective details.
     *
     * @param String $idOrganisation The ID of the organization for which to retrieve authorizations and permissions.
     *
     * @return Array|null An Array containing the authorizations and permissions associated with the organization.
     */
    public function getHabilitationsOrganisations(String $idOrganisation): ?array
    {
        $this->logger->debug('Get habilitations organisations', ['idOrganisation' => $idOrganisation]);

        try {
            $organisationAutorisationRepository = $this->entityManager->getRepository(OrganisationAutorisation::class);
            $organisationAutorisation = $organisationAutorisationRepository->findActiveOrganisations($idOrganisation);
        } catch (\Exception $e) {
            return null;
        }

        $habilitationsOrganisations = [];
        foreach ($organisationAutorisation as $org) {
            $habilitationsOrganisations[] = [
                'date_debut' => $org->getDateDebut() ? $org->getDateDebut()->format('d/m/Y') : null,
                'date_fin' => $org->getDateFin() ? $org->getDateFin()->format('d/m/Y') : null,
                'perimetre' => $org->getPerimetre(),
                'type_autorisation' => $org->getTypeAutorisation(),
            ];
        }
        return $habilitationsOrganisations;
    }
}
