<?php

namespace App\Service;

use App\Controller\Http\Responses\HabilitationReponse;
use App\Controller\Http\Responses\Status;
use App\Mapper\EtablissementMapper;
use App\Mapper\UtilisateurMapper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class AdminService
{
    private LoggerInterface $logger;
    private UtilisateurMapper $utilisateurMapper;
    private EtablissementMapper $etablissementMapper;
    private OrganisationAutorisationService $organisationAutorisationService;
    private EtablissementService $etablissementService;
    private HabilitationsDomainesService $habilitationsDomainesService;
    private UtilisateurService $utilisateurService;

    public function __construct(
        LoggerInterface $logger,
        UtilisateurMapper $utilisateurMapper,
        EtablissementMapper $etablissementMapper,
        OrganisationAutorisationService $organisationAutorisationService,
        EtablissementService $etablissementService,
        HabilitationsDomainesService $habilitationsDomainesService,
        UtilisateurService $utilisateurService
    ) {
        $this->logger = $logger;
        $this->utilisateurMapper = $utilisateurMapper;
        $this->etablissementMapper = $etablissementMapper;
        $this->organisationAutorisationService = $organisationAutorisationService;
        $this->etablissementService = $etablissementService;
        $this->habilitationsDomainesService = $habilitationsDomainesService;
        $this->utilisateurService = $utilisateurService;
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
     * @return array An array containing user information and authorizations.
     */
    public function getUserInfo(String $idUser): array
    {
        $this->logger->info('Get user info from devel-plage-infoservice', ['idUser' => $idUser]);

        $response = new HabilitationReponse();
        $response->setHabilitationsDomaines([]);
        $response->setHabilitationsScansante([]);
        $ipe = null;

        # 1 - Récupération des informations de l’utilisateur
        $develXml = $this->utilisateurService->getDevelXml($idUser);
        $ipe = isset($develXml->ipe) ? (string) $develXml->ipe : null;
        $userData = $this->utilisateurMapper->formatInfoUserXml($develXml);
        $UtilisateurDto =   $this->utilisateurMapper->mapToUtilisateurDto($userData);

        $response->setInfoUtilisateur($UtilisateurDto);

        # 2 - Récupération des habilitations des organisations
        $organisationAutorisations = $this->organisationAutorisationService->getOrganisationAutorisations($develXml->organisation->id);
        $habilitationsOrganisations = $this->organisationAutorisationService->parseOrganisationAutorisation($organisationAutorisations);

        $response->setHabilitationsOrganisation($habilitationsOrganisations);

        # 3 - Récupération des habilitations domaines
        $id = isset($develXml->niveau->id) ? (string) $develXml->niveau->id : null;
        if ($id == 3) { // TODO: recup niveau etablissement id via la database
            $finessDomainsXml = $this->etablissementService->getFinessDomainXml($ipe);
            $finessDomains = $this->etablissementMapper->formatESInfoXml($finessDomainsXml);

            $response->setHabilitationsDomaines($finessDomains);
        }

        # 4 - Récupération des habilitations scansante
        $roleScanSante = $this->habilitationsDomainesService->getRoleScanSante($response->getHabilitationsDomaines());
        $response->setHabilitationsScansante($roleScanSante);

        $response->setRetour(Status::ok()->toArray());

        return $response->toArray();
    }
}
