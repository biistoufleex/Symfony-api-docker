<?php

namespace App\Service;

use App\Controller\Http\Responses\HabilitationReponse;
use Psr\Log\LoggerInterface;
use App\Service\Utils\ApiUtils;

class ApiService
{
    private LoggerInterface $logger;
    private ApiUtils $apiUtils;
    private $infosUtilisateur;
    private HabilitationReponse $response;
    private string $ipe;

    public function __construct(LoggerInterface $logger, ApiUtils $apiUtils)
    {
        $this->logger = $logger;
        $this->apiUtils = $apiUtils;
        $this->response = new HabilitationReponse();
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
        $this->infosUtilisateur = $this->apiUtils->getDevelPlageXml($idUser);

        // Stock l'ipe pour l'etape 3
        $this->ipe = isset($this->infosUtilisateur->ipe) ? (string) $this->infosUtilisateur->ipe : null;

        if ($this->infosUtilisateur === null) {
            $this->logger->error('Erreur lors de la récupération des informations de l\'utilisateur', ['idUser' => $idUser]);
            throw new \Exception('Erreur lors de la récupération des informations de l\'utilisateur');
        } else if ($this->infosUtilisateur->exception) {
            $this->logger->error($this->infosUtilisateur->exception->libelle, ['idUser' => $idUser]);
            throw new \Exception($this->infosUtilisateur->exception->libelle);
        }

        $this->infosUtilisateur = $this->apiUtils->formatInfoUserXml($this->infosUtilisateur);
        $this->infosUtilisateur = $this->apiUtils->mapToUtilisateurDto($this->infosUtilisateur);

        $this->response->setInfoUtilisateur($this->infosUtilisateur);
    }

    private function getGestAuthHabilitations(): void
    {
        /**
         * 2 - Récupération des habilitations issues de GESTAUTH
         * 
         * .1 Récupérer les habilitations déclarées dans GESTAUTH via la table organisation_autorisation
         * .2 Parser les “domaines-rôles” pour récupérer les rôles du domaine “SCANSANTE”
         */
        $habilitationsOrganisations = $this->apiUtils->getHabilitationsOrganisations($this->infosUtilisateur->organisation->id);

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
        if ($this->infosUtilisateur->niveau->id === 3) { // TODO: recup niveau etablissement id via la database
            $finessDomainsXml = $this->apiUtils->getESInfoXml($this->ipe);
            $finessDomains = $this->apiUtils->formatESInfoXml($finessDomainsXml);
            $this->response->setHabilitationsDomaines($finessDomains);
        }
    }
}
