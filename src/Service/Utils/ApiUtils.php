<?php

namespace App\Service\Utils;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\OrganisationAutorisation;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Dto\OrganisationDto;
use App\Dto\UtilisateurDto;
use App\Dto\NiveauDto;
use DateTime;
use SimpleXMLElement;

class ApiUtils
{

    private HttpClientInterface $client;
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;

    public function __construct(HttpClientInterface $client, LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    /**
     * Retrieves user information from the Devel Plage InfoService API in XML format.
     *
     * This method sends a GET request to the Devel Plage InfoService API to fetch user information
     * based on the provided user ID. The user information is returned as an XML document in the response.
     *
     * @param string $idUser The user ID for which to fetch information.
     *
     * @return SimpleXMLElement|null A SimpleXMLElement object containing the user information in XML format
     *                            or null if an error occurs during the request.
     *
     * @throws \Exception If an exception occurs while making the API request, it is caught and null is returned.
     */
    public function getDevelPlageXml(String $idUser): ?SimpleXMLElement
    {
        try {
            $response = $this->client->request(
                'GET',
                'https://devel-plage-infoservice.atih.sante.fr/plage-infoservice/getUserInfo.do',
                [
                    'query' => [
                        'idUser' => $idUser,
                    ],
                    'timeout' => 3,
                ]
            );

            $xml = simplexml_load_string($response->getContent());
        } catch (\Exception $e) {
            return null;
        }

        return $xml;
    }

    /**
     * Formats user information from an XML response into an array.
     *
     * This method takes an XML object as input and extracts relevant user information from it.
     * The extracted information is then organized into an associative array and returned.
     *
     * @param SimpleXMLElement $xml The XML object containing user information.
     *
     * @return array An associative array containing formatted user information.
     */
    public function formatInfoUserXml($xml): array
    {
        $this->logger->debug('Format user xml');

        $roles_scansante = [];

        if (isset($xml->drs->dr->roles->role)) {
            foreach ($xml->drs->dr->roles->role as $role) {
                if ($role) {
                    array_push($roles_scansante, (string) $role->libelle);
                }
            }
        }

        $infosUtilisateur = [
            'id' => isset($xml->id) ? (int) $xml->id : null,
            'nom' => isset($xml->nom) ? (string) $xml->nom : null,
            'prenom' => isset($xml->prenom) ? (string)  $xml->prenom : null,
            'email' => isset($xml->email) ? (string)  $xml->email : null,
            'niveau' => [
                'id' => isset($xml->niveau->id) ? (int) $xml->niveau->id : null,
                'libelle' => isset($xml->niveau->libelle) ? (string) $xml->niveau->libelle : null,
            ],
            'organisation' => [
                'id' => isset($xml->organisation->id) ? (string) $xml->organisation->id : null,
                'libelle' => isset($xml->organisation->libelle) ? (string) $xml->organisation->libelle : null,
            ],
            'roles_scansante' => $roles_scansante
        ];

        return $infosUtilisateur;
    }

    /**
     * Retrieves Establishment (ES) information from the Devel Plage InfoService API in XML format.
     *
     * This method sends a GET request to the Devel Plage InfoService API to fetch Establishment information
     * based on the provided Establishment ID (EPI). The Establishment information is returned as an XML document in the response.
     *
     * @param string $epi The Establishment ID (EPI) for which to fetch information.
     *
     * @return SimpleXMLElement|null A SimpleXMLElement object containing the Establishment information in XML format
     *                            or null if an error occurs during the request.
     *
     * @throws \Exception If an exception occurs while making the API request, it is caught, and null is returned.
     */
    public function getESInfoXml(string $epi): ?SimpleXMLElement
    {
        try {
            $response = $this->client->request(
                'GET',
                'https://devel-plage-infoservice.atih.sante.fr/plage-infoservice/getESInfo.do',
                [
                    'query' => [
                        'ipe' => $epi,
                    ],
                    'timeout' => 3,
                ]
            );

            $xml = simplexml_load_string($response->getContent());
        } catch (\Exception $e) {
            return null;
        }

        return $xml;
    }

    /**
     * Formats Establishment (ES) information from an XML response into an array.
     *
     * This method takes an XML object as input and extracts relevant Establishment information from it.
     * The extracted information is then organized into an associative array and returned.
     *
     * @param SimpleXMLElement $xml The XML object containing Establishment information.
     *
     * @return array An associative array containing formatted Establishment information.
     */
    public function formatESInfoXml($xml): ?array
    {
        $this->logger->debug('Format ESInfo xml');

        $domainesPerimetres = [
            'ANCRE' => 'Finances',
            'RTC' => 'Finances',
            'TDBEMS' => 'Médico-Social',
            'BILANSOCIAL' => 'RH',
            'TDBESMS' => 'Médico-Social', // TODO: remove this
        ];

        $habilitationsDomaines = [];

        if (isset($xml->finessDomaines->finessDomaine)) {
            foreach ($xml->finessDomaines->finessDomaine as $finessDomaine) {
                if ($finessDomaine && array_key_exists((string) $finessDomaine->domaine->libelle, $domainesPerimetres)) {
                    $habilitationsDomaines[] = [
                        'date_debut' => isset($finessDomaine->dateDebut) ?
                            (new DateTime((string) $finessDomaine->dateDebut))->format('d/m/Y') : null,

                        'date_fin' => isset($finessDomaine->dateFin) ?
                            (new DateTime((string) $finessDomaine->dateFin))->format('d/m/Y') : null,

                        'perimetre' => $domainesPerimetres[(string) $finessDomaine->domaine->libelle],
                        'type_autorisation' => 'Domaine',
                    ];
                }
            }
        }

        return $habilitationsDomaines;
    }

    /**
     * Maps an array of user information to a UtilisateurDto object.
     *
     * This method takes an array of user information and maps it to a UtilisateurDto object, which is used
     * to represent a user with structured data.
     *
     * @param array $infosUtilisateur An array containing user information.
     *
     * @return UtilisateurDto A UtilisateurDto object representing the user with mapped information.
     */
    public function mapToUtilisateurDto(array $infosUtilisateur): UtilisateurDto
    {
        $this->logger->debug('map to UtilisateurDto');

        $utilisateurDto = new UtilisateurDto(
            $infosUtilisateur['id'],
            $infosUtilisateur['nom'],
            $infosUtilisateur['prenom'],
            $infosUtilisateur['email'],
            new NiveauDto($infosUtilisateur['niveau']['id'], $infosUtilisateur['niveau']['libelle']),
            new OrganisationDto($infosUtilisateur['organisation']['id'], $infosUtilisateur['organisation']['libelle']),
            $infosUtilisateur['roles_scansante'],
            []
        );

        return $utilisateurDto;
    }

    /**
     * Get the authorizations and permissions for a specific organization.
     *
     * This method retrieves the authorizations and permissions associated with a particular organization
     * identified by its ID. It queries the database to find active organizations and their respective details.
     *
     * @param string $idOrganisation The ID of the organization for which to retrieve authorizations and permissions.
     *
     * @return array An array containing the authorizations and permissions associated with the organization.
     */
    public function getHabilitationsOrganisations(String $idOrganisation): array
    {
        $organisationAutorisationRepository = $this->entityManager->getRepository(OrganisationAutorisation::class);
        $organisationAutorisation = $organisationAutorisationRepository->findActiveOrganisations($idOrganisation);

        $habilitationsOrganisations = [];
        foreach ($organisationAutorisation as $org) {
            $habilitationsOrganisations[] = [
                'date_debut' => $org->getDateDebut(),
                'date_fin' => $org->getDateFin(),
                'perimetre' => $org->getPerimetre(),
                'type_autorisation' => $org->getTypeAutorisation(),
            ];
        }
        return $habilitationsOrganisations;
    }

    /**
     * Retrieve an Organization Authorization by Perimeter.
     *
     * This method searches for an Organization Authorization within a given array of Organization Authorizations
     * based on the provided perimeter. It returns the matching Organization Authorization if found, or null if not found.
     *
     * @param array $organisationAutorisations An array of Organization Authorizations to search within.
     * @param string $perimetre The perimeter to match when searching for an Organization Authorization.
     *
     * @return OrganisationAutorisation|null The matching Organization Authorization, or null if not found.
     */
    public function getOrganisationAutorisationByPerimetre(array $organisationAutorisations, string $perimetre): ?OrganisationAutorisation
    {
        foreach ($organisationAutorisations as $organisationAutorisation) {
            if ($organisationAutorisation->getPerimetre() === $perimetre) {
                return $organisationAutorisation;
            }
        }

        return null;
    }

    public function createFakeData(): void
    {
        $organisationAutorisationRepository = $this->entityManager->getRepository(OrganisationAutorisation::class);
        $testRows = [
            [
                'identifiant_organisation_plage' => 'ATIH',
                'date_debut' => '2023-10-11',
                'date_fin' => null,
                'perimetre' => 'Finance',
                'type_autorisation' => 'Accs permanent',
            ],
            [
                'identifiant_organisation_plage' => 'ATIH',
                'date_debut' => '2023-10-11',
                'date_fin' => null,
                'perimetre' => 'Institution',
                'type_autorisation' => 'PDH',
            ],
            [
                'identifiant_organisation_plage' => 'SYNERPA',
                'date_debut' => '2023-10-12',
                'date_fin' => null,
                'perimetre' => 'Mdico Social',
                'type_autorisation' => 'Accs permanent',
            ],
            [
                'identifiant_organisation_plage' => 'SYNERPA',
                'date_debut' => '2023-10-12',
                'date_fin' => null,
                'perimetre' => 'Ressources Humaines',
                'type_autorisation' => 'PDH',
            ],
            [
                'identifiant_organisation_plage' => 'PQOWIEASD',
                'date_debut' => '2023-10-12',
                'date_fin' => null,
                'perimetre' =>  'Institution',
                'type_autorisation' => 'Autorisation en propre',
            ],
            [
                'identifiant_organisation_plage' => 'PQOWIEASD',
                'date_debut' => '2023-10-12',
                'date_fin' => null,
                'perimetre' => 'Activit',
                'type_autorisation' => 'Autorisation en propre',
            ],
            [
                'identifiant_organisation_plage' => 'BDSTR',
                'date_debut' => '2023-10-12',
                'date_fin' => null,
                'perimetre' => 'Finance',
                'type_autorisation' => 'PDH',
            ],
            [
                'identifiant_organisation_plage' => 'BDSTR',
                'date_debut' => '2023-10-12',
                'date_fin' => null,
                'perimetre' =>  'Institution',
                'type_autorisation' => 'PDH',
            ],
            [
                'identifiant_organisation_plage' => 'QWER',
                'date_debut' => '2023-10-13',
                'date_fin' => null,
                'perimetre' =>  'Activit',
                'type_autorisation' => 'Accs permanent',
            ],
            [
                'identifiant_organisation_plage' => 'QWER',
                'date_debut' => '2023-10-13',
                'date_fin' => null,
                'perimetre' => 'Mdico Social',
                'type_autorisation' => 'Accs permanent',
            ],
            [
                'identifiant_organisation_plage' => 'ACQWWWE',
                'date_debut' => '2023-10-13',
                'date_fin' => null,
                'perimetre' => 'Ressources Humaines',
                'type_autorisation' => 'PDH',
            ],
            [
                'identifiant_organisation_plage' => 'ACQWWWE',
                'date_debut' => '2023-10-13',
                'date_fin' => null,
                'perimetre' => 'Finance',
                'type_autorisation' => 'Autorisation en propre',
            ],
            [
                'identifiant_organisation_plage' => 'ASDASQ',
                'date_debut' => '2023-10-13',
                'date_fin' => null,
                'perimetre' => 'Activit',
                'type_autorisation' => 'Accs permanent',
            ],
            [
                'identifiant_organisation_plage' => 'ASDASQ',
                'date_debut' => '2023-10-13',
                'date_fin' => null,
                'perimetre' => 'Institution',
                'type_autorisation' => 'Accs permanent',
            ],
        ];

        foreach ($testRows as $testRow) {
            $org = new OrganisationAutorisation();
            $org->setIdentifiantOrganisationPlage($testRow['identifiant_organisation_plage']);
            $org->setDateDebut(new \DateTime($testRow['date_debut']));
            $org->setDateFin($testRow['date_fin'] ? new \DateTime($testRow['date_fin']) : null);
            $org->setPerimetre($testRow['perimetre']);
            $org->setTypeAutorisation($testRow['type_autorisation']);

            $this->entityManager->persist($org);
        }

        $this->entityManager->flush();
    }
}
