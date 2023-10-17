<?php

namespace App\Mapper;

use App\Dto\UtilisateurDto;
use Psr\Log\LoggerInterface;
use App\Dto\OrganisationDto;
use App\Dto\NiveauDto;

class UtilisateurMapper
{

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Maps an Array of user information to a UtilisateurDto object.
     *
     * This method takes an Array of user information and maps it to a UtilisateurDto object, which is used
     * to represent a user with structured data.
     *
     * @param Array $infosUtilisateur An Array containing user information.
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
        );

        return $utilisateurDto;
    }

    /**
     * Formats user information from an XML response into an Array.
     *
     * This method takes an XML object as input and extracts relevant user information from it.
     * The extracted information is then organized into an associative Array and returned.
     *
     * @param SimpleXMLElement $xml The XML object containing user information.
     *
     * @return Array An associative Array containing formatted user information.
     */
    public function formatInfoUserXml($xml): array
    {
        $this->logger->debug('Format user xml');

        $roles_scansante = [];

        if (isset($xml->drs->dr->roles->role)) {
            foreach ($xml->drs->dr->roles->role as $role) {
                if ($role) {
                    Array_push($roles_scansante, (string) $role->libelle);
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
}
