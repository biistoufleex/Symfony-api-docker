<?php

namespace App\Service;

use App\Controller\Http\Responses\HabilitationReponse;
use App\Controller\Http\Responses\Status;
use App\Service\Utils\ApiUtils;
use Psr\Log\LoggerInterface;

class ApiService
{
    private LoggerInterface $logger;
    private ApiUtils $apiUtils;
    private $data;
    private HabilitationReponse $response;
    private ?String $ipe;

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
        $this->data = $this->apiUtils->getDevelPlageXml($idUser);

        if ($this->data === null) {
            $this->logger->error('Erreur lors de la récupération des informations de l\'utilisateur', ['idUser' => $idUser]);
            throw new \Exception('Erreur lors de la récupération des informations de l\'utilisateur');
        } else if ($this->data->exception) {
            $this->logger->error($this->data->exception->libelle, ['idUser' => $idUser]);
            throw new \Exception($this->data->exception->libelle);
        }

        // Stock l'ipe pour l'etape 3
        $this->ipe = isset($this->data->ipe) ? (string) $this->data->ipe : null;

        $this->data = $this->apiUtils->formatInfoUserXml($this->data);
        $this->data = $this->apiUtils->mapToUtilisateurDto($this->data);

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
        $habilitationsOrganisations = $this->apiUtils->getHabilitationsOrganisations($this->data->organisation->id);
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
            $finessDomainsXml = $this->apiUtils->getESInfoXml($this->ipe);
            $finessDomains = $this->apiUtils->formatESInfoXml($finessDomainsXml);
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
        if (!$this->response->getHabilitationsDomaines()) {
            throw new \Exception('Erreur lors de la récupération des habilitations issues des domaines Plage');
        }

        $roleScanSante = ['lecteur'];

        foreach ($this->response->getHabilitationsDomaines() as $habilitationDomaine) {
            $roleScanSante[] = "lecteur_" . $habilitationDomaine['perimetre'];
        }

        $this->response->setHabilitationsScansante($roleScanSante);
    }
}
