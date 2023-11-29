<?php

namespace App\Service\Api;

use App\constants\MessageConstants;
use App\Entity\Api\OrganisationAutorisation;
use App\Repository\Api\OrganisationAutorisationRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;

class OrganisationAutorisationService
{
    private const DEPOT_MR_005 = 'depot_mr005';
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;
    private OrganisationAutorisationRepository $organisationAutorisationRepository;

    public function __construct(
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
        OrganisationAutorisationRepository $organisationAutorisationRepository)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->organisationAutorisationRepository = $organisationAutorisationRepository;
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
     * @return array<string, mixed>|null An array containing the active organization authorizations and permissions.
     *
     * @throws Exception If there is an issue with retrieving the organization authorizations, an exception is thrown,
     *                    and the issue is logged with details.
     */
    public function getOrganisationAutorisations(String $idOrganisation): ?array
    {
        $this->logger->debug('Get habilitations organisations', ['idOrganisation' => $idOrganisation]);

        try {
            /** @var OrganisationAutorisationRepository $organisationAutorisationRepository */
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

    /**
     * @param array<string, mixed> $data
     * @return void
     * @throws Exception
     */
    public function createOrganisationAutorisation(array $data): void
    {
        $this->logger->info('Create organisation autorisation');

        $organisationAutorisation = new OrganisationAutorisation();
        $organisationAutorisation->setIdentifiantOrganisationPlage("IPE");

        try {
            $dateAttribution = new DateTime(
                $data[self::DEPOT_MR_005]['dateAttribution']['year'] . '-' .
                $data[self::DEPOT_MR_005]['dateAttribution']['month'] . '-' .
                $data[self::DEPOT_MR_005]['dateAttribution']['day']);
        } catch (Exception $e) {
            throw new Exception("Date attribution is not valid");
        }
        $organisationAutorisation->setDateDebut($dateAttribution);
        $organisationAutorisation->setDateFin(null);
        $organisationAutorisation->setPerimetre('activite');
        $organisationAutorisation->setTypeAutorisation('mr005');

        $this->entityManager->persist($organisationAutorisation);
        $this->entityManager->flush();
    }
}
