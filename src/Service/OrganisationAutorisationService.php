<?php

namespace App\Service;

use App\constants\MessageConstants;
use App\Entity\OrganisationAutorisation;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
     * Get active authorizations and permissions for a specific organization.
     *
     * This method retrieves the active authorizations and permissions associated with a particular organization
     * identified by its ID. It queries the database to find active organizations and their respective details.
     *
     * @param string $idOrganisation The ID of the organization for which to retrieve
     *                               active authorizations and permissions.
     *
     * @return array<int, mixed>|null An array containing the active organization authorizations and permissions.
     *
     * @throws Exception If there is an issue with retrieving the organization authorizations, an exception is thrown,
     *                    and the issue is logged with details.
     */
    public function getOrganisationAutorisations(String $idOrganisation): ?array
    {
        $this->logger->debug('Get habilitations organisations', ['idOrganisation' => $idOrganisation]);

        try {
            $organisationAutorisationRepository = $this->entityManager->getRepository(OrganisationAutorisation::class);
            $organisationAutorisation = $organisationAutorisationRepository->findActiveOrganisations($idOrganisation);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), ['idOrganisation' => $idOrganisation]);
            throw new Exception(MessageConstants::PROBLEME_RECUP_OGRANISATION_AUTORISATION);
        }
        return $organisationAutorisation;
    }

    /**
     * Parse an array of Organization Authorizations into a structured array.
     *
     * This method takes an array of Organization Authorizations and converts them into a structured array
     * with specific formatting for each element. It provides information about the authorizations, including
     * start and end dates, perimeter, and type of authorization.
     *
     * @param OrganisationAutorisation[] $organisationAutorisation An array of Organization Authorizations to parse.
     *
     * @return array<int, mixed> An array containing the parsed and formatted Organization Authorizations.
    */
    public function parseOrganisationAutorisation(array $organisationAutorisation): array
    {
        $habilitationsOrganisations = [];

        foreach ($organisationAutorisation as $org) {
            $habilitationsOrganisations[] = [
                'date_debut' => $org->getDateDebut()?->format('d/m/Y'),
                'date_fin' => $org->getDateFin()?->format('d/m/Y'),
                'perimetre' => $org->getPerimetre(),
                'type_autorisation' => $org->getTypeAutorisation(),
            ];
        }
        return $habilitationsOrganisations;
    }
}
