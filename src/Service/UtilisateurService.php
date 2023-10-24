<?php

namespace App\Service;

use App\constants\MessageConstants;
use App\Controller\Http\Responses\HabilitationReponse;
use App\Controller\Http\Responses\Status;
use App\Entity\Utilisateur;
use App\Mapper\EtablissementMapper;
use App\Mapper\UtilisateurMapper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UtilisateurService
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;
    private UtilisateurMapper $utilisateurMapper;
    private EtablissementMapper $etablissementMapper;
    private OrganisationAutorisationService $organisationAutorisationService;
    private EtablissementService $etablissementService;
    private RoleApplicationService $roleApplicationService;

    public function __construct(
        LoggerInterface                 $logger,
        EntityManagerInterface          $entityManager,
        EtablissementMapper             $etablissementMapper,
        OrganisationAutorisationService $organisationAutorisationService,
        EtablissementService            $etablissementService,
        RoleApplicationService    $roleApplicationService,
        UtilisateurMapper               $utilisateurMapper)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->etablissementMapper = $etablissementMapper;
        $this->organisationAutorisationService = $organisationAutorisationService;
        $this->etablissementService = $etablissementService;
        $this->roleApplicationService = $roleApplicationService;
        $this->utilisateurMapper = $utilisateurMapper;
    }

    /**
     * Get user information and authorizations for a specific user.
     *
     * This method retrieves user information and authorizations for a user identified by their ID.
     * It performs several steps to gather user data, organization authorizations, domain authorizations, and
     * scansante authorizations, and then assembles this information into a structured response.
     *
     * @param string $idUser The ID of the user for which to retrieve information and authorizations.
     *
     * @return array<string, mixed> An array containing user information and authorizations.
     * @throws Exception|TransportExceptionInterface
     */
    public function getUserInfo(string $idUser): array
    {
        $this->logger->info('Get user info from devel-plage-infoservice', ['idUser' => $idUser]);

        $response = new HabilitationReponse();
        $response->setHabilitationsDomaines([]);
        $response->setHabilitationsScansante([]);

        # 1 - Récupération des informations de l’utilisateur
        $develXml = $this->getDevelXml($idUser);
        $ipe = isset($develXml->ipe) ? (string)$develXml->ipe : null;
        $userData = $this->utilisateurMapper->formatInfoUserXml($develXml);
        $UtilisateurDto = $this->utilisateurMapper->mapToUtilisateurDto($userData);

        $response->setInfoUtilisateur($UtilisateurDto);

        # 2 - Récupération des habilitations des organisations
        $organisationAutorisations = $this->organisationAutorisationService->getOrganisationAutorisations($develXml->organisation->id);
        $habilitationsOrganisations = $this->organisationAutorisationService->parseOrganisationAutorisation($organisationAutorisations);

        $response->setHabilitationsOrganisation($habilitationsOrganisations);

        # 3 - Récupération des habilitations domaines
        $id = isset($develXml->niveau->id) ? (string)$develXml->niveau->id : null;
        if ($id == 3) { // TODO: recup niveau etablissement id via la database
            $finessDomainsXml = $this->etablissementService->getFinessDomainXml($ipe);
            $finessDomains = $this->etablissementMapper->formatESInfoXml($finessDomainsXml);

            $response->setHabilitationsDomaines($finessDomains);
        }

        # 4 - Récupération des habilitations scansante
        $roleScanSante = $this->roleApplicationService->getRoleScanSante(
            $response->getHabilitationsDomaines(),
            $response->getHabilitationsOrganisation()
        );
        $response->setHabilitationsScansante($roleScanSante);

        $response->setRetour(Status::ok()->toArray());

        return $response->toArray();
    }

    /**
     * Get user information from the Devel Plage InfoService API in XML format.
     *
     * This method retrieves user information from the Devel Plage InfoService API based on the provided user ID.
     * It queries the API and returns the information in XML format.
     *
     * @param string $idUser The user ID for which to fetch information.
     *
     * @return SimpleXMLElement|null A SimpleXMLElement object containing the user information in XML format
     *                            or null if there is an issue with the InfoService API communication.
     *
     * @throws Exception If there is a problem with the InfoService API communication or if the API returns an exception,
     *                    an exception is thrown, and the issue is logged with details.
     */
    public function getDevelXml(string $idUser): ?SimpleXMLElement
    {
        $plageXml = $this->entityManager->getRepository(Utilisateur::class)->getDevelPlageXml($idUser);

        if (!$plageXml) {
            $this->logger->error(MessageConstants::PROBLEME_COMMUNICATION_INFOSERVICE_USER, ['idUser' => $idUser]);
            throw new Exception(MessageConstants::PROBLEME_COMMUNICATION_INFOSERVICE_USER);
        }
        if ($plageXml->exception) {
            $this->logger->error($plageXml->exception->libelle, ['idUser' => $idUser]);
            throw new Exception($plageXml->exception->libelle);
        }

        return $plageXml;
    }
}
