<?php

namespace App\Service;

use App\constants\MessageConstants;
use App\Controller\Http\Responses\HabilitationReponse;
use App\Controller\Http\Responses\Status;
use App\Entity\Utilisateur;
use App\Mapper\EtablissementMapper;
use App\Mapper\UtilisateurMapper;
use App\Repository\EtablissementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class UtilisateurService
{
    private LoggerInterface $logger;
    private $data;
    private HabilitationReponse $response;
    private ?String $ipe;
    private EntityManagerInterface $entityManager;
    private UtilisateurMapper $utilisateurMapper;
    private EtablissementMapper $etablissementMapper;
    private EtablissementRepository $etablissementRepository;
    private OrganisationAutorisationService $organisationAutorisationService;

    public function __construct(
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
        UtilisateurMapper $utilisateurMapper,
        EtablissementMapper $etablissementMapper,
        EtablissementRepository $etablissementRepository,
        OrganisationAutorisationService $organisationAutorisationService
    ) {
        $this->logger = $logger;
        $this->response = new HabilitationReponse();
        $this->entityManager = $entityManager;
        $this->utilisateurMapper = $utilisateurMapper;
        $this->etablissementMapper = $etablissementMapper;
        $this->etablissementRepository = $etablissementRepository;
        $this->organisationAutorisationService = $organisationAutorisationService;
    }

    /**
     * Get user info from devel-plage-infoservice
     * 
     * @param String $idUser
     * @return array
     */
    public function getUserInfo(String $idUser): array
    {
        $this->logger->info('Get user info from devel-plage-infoservice', ['idUser' => $idUser]);

        $this->getUserAcces($idUser);

        $this->getGestAuthHabilitations();

        $this->getDomainesPlageHabilitations();

        $this->getRoleScanSante();

        $this->response->setRetour(Status::ok()->toArray());
        return $this->response->toArray();
    }

    private function getUserAcces(string $idUser): void
    {
        /**
         * 1 - Récupération des informations et des droits de l’utilisateur
         * 
         * .1 Appel à “infoservice”
         * .2 Parser les “domaines-rôles” pour récupérer les rôles du domaine “SCANSANTE”
         */
        $this->data = $this->entityManager->getRepository(Utilisateur::class)->getDevelPlageXml($idUser);

        if ($this->data === null) {
            $this->logger->error(MessageConstants::ERREUR_RECUPERATION_INFO_USER, ['idUser' => $idUser]);
            throw new \Exception(MessageConstants::ERREUR_RECUPERATION_INFO_USER);
        } else if ($this->data->exception) {
            $this->logger->error($this->data->exception->libelle, ['idUser' => $idUser]);
            throw new \Exception($this->data->exception->libelle);
        }

        // Stock l'ipe pour l'etape 3
        $this->ipe = isset($this->data->ipe) ? (string) $this->data->ipe : null;

        $this->data = $this->utilisateurMapper->formatInfoUserXml($this->data);

        $this->data = $this->utilisateurMapper->mapToUtilisateurDto($this->data);

        $this->response->setInfoUtilisateur($this->data);
    }

    private function getGestAuthHabilitations(): void
    {
        /**
         * 2 - Récupération des habilitations issues de GESTAUTH
         * 
         * .1 Récupérer les habilitations déclarées dans GESTAUTH via la table organisation_autorisation
         * .2 Parser les “domaines-rôles” pour récupérer les rôles du domaine “SCANSANTE”
         */
        $habilitationsOrganisations = $this->organisationAutorisationService->getHabilitationsOrganisations($this->data->organisation->id);
        $this->response->setHabilitationsOrganisation($habilitationsOrganisations);
    }

    private function getDomainesPlageHabilitations(): void
    {
        /**
         * 3 - Récupération des habilitations issues des domaines Plage
         * .1 Dans le cas d’un niveau établissement,
         *    il faut voir si certains domaines sont présents pour cette structure,
         *    et si oui donner l’information en sortie.
         */

        $this->response->setHabilitationsDomaines([]);

        if ($this->data->niveau->id === 3) { // TODO: recup niveau etablissement id via la database
            $finessDomainsXml = $this->etablissementRepository->getESInfoXml($this->ipe);
            $finessDomains = $this->etablissementMapper->formatESInfoXml($finessDomainsXml);
            $this->response->setHabilitationsDomaines($finessDomains);
        }
    }

    private function getRoleScanSante(): void
    {
        /**
         * 4 - Détermination des rôles propres à Scansanté
         * .1 En fonction des rôles du domaines SCANSANTE et des habilitations 
         *    on détermine les rôles propres à l’application Scansanté
         */

        // TODO: 17441 - que faire en cas d'absence de habilitationsDomaines ?
        // if (!$this->response->getHabilitationsDomaines()) {
        //     throw new \Exception('Erreur lors de la récupération des habilitations issues des domaines Plage');
        // }

        $roleScanSante = ['lecteur'];

        foreach ($this->response->getHabilitationsDomaines() as $habilitationDomaine) {
            if (!in_array("lecteur_" . $habilitationDomaine['perimetre'], $roleScanSante)) {
                $roleScanSante[] = "lecteur_" . $habilitationDomaine['perimetre'];
            }
        }

        $this->response->setHabilitationsScansante($roleScanSante);
    }
}
