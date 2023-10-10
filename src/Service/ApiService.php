<?php

namespace App\Service;

use App\Dto\NiveauDto;
use App\Dto\OrganisationDto;
use App\Dto\UtilisateurDto;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiService
{
    private HttpClientInterface $client;


    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Get user info from devel-plage-infoservice
     * 
     * @param String $idUser
     * @return UtilisateurDto
     */
    public function getUserInfo(String $idUser): UtilisateurDto
    {
        try {
            $response = $this->client->request(
                'GET',
                'https://devel-plage-infoservice.atih.sante.fr/plage-infoservice/getUserInfo.do',
                [
                    'query' => [
                        'idUser' => $idUser,
                    ],
                ]
            );
        } catch (\Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface $e) {
            return ['error' => $e->getMessage()];
        }

        $xml = simplexml_load_string($response->getContent());

        $infosUtilisateur = $this->formatXml($xml);

        $utilisateurDto = $this->mapToUtilisateurDto($infosUtilisateur);

        return $utilisateurDto;
    }

    private function formatXml($xml): array
    {

        $roles_scansante = [];

        foreach ($xml->drs->dr->roles->role as $role) {
            array_push($roles_scansante, (string) $role->libelle);
        }

        $infosUtilisateur = [
            'id' => (int) $xml->id,
            'nom' => (string) $xml->nom,
            'prenom' => (string) $xml->prenom,
            'email' => (string) $xml->email,
            'niveau' => [
                'id' => (int) $xml->niveau->id,
                'libelle' => (string) $xml->niveau->libelle,
            ],
            'organisation' => [
                'id' => (string) $xml->organisation->id,
                'libelle' => (string) $xml->organisation->libelle,
            ],
            'roles_scansante' => $roles_scansante
        ];

        return $infosUtilisateur;
    }

    private function mapToUtilisateurDto(array $infosUtilisateur): UtilisateurDto
    {
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
}
