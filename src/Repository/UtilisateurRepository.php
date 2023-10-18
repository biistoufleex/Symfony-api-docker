<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 *
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method SimpleXMLElement|null getDevelPlageXml(String $idUser)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    private HttpClientInterface $client;
    private LoggerInterface $logger;
    private String $getUserInfoUrl;

    public function __construct(ManagerRegistry $registry, HttpClientInterface $client, LoggerInterface $logger, String $getUserInfoUrl)
    {
        parent::__construct($registry, Utilisateur::class);
        $this->client = $client;
        $this->logger = $logger;
        $this->getUserInfoUrl = $getUserInfoUrl;
    }

    /**
     * Retrieves user information from the Devel Plage InfoService API in XML format.
     *
     * This method sends a GET request to the Devel Plage InfoService API to fetch user information
     * based on the provided user ID. The user information is returned as an XML document in the response.
     *
     * @param String $idUser The user ID for which to fetch information.
     *
     * @return SimpleXMLElement|null A SimpleXMLElement object containing the user information in XML format
     *                            or null if an error occurs during the request.
     *
     * @throws \Exception If an exception occurs while making the API request, it is caught and null is returned.
     */
    public function getDevelPlageXml(String $idUser): ?SimpleXMLElement
    {
        $this->logger->debug('Get devel plage xml');
    
        try {
            $response = $this->client->request(
                'GET',
                $this->getUserInfoUrl,
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
}
