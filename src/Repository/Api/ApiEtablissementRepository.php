<?php

namespace App\Repository\Api;

use Exception;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiEtablissementRepository
{
    private HttpClientInterface $client;
    private LoggerInterface $logger;
    private String $getEtablissementInfoUrl;

    public function __construct(HttpClientInterface $client, LoggerInterface $logger, String $getEtablissementInfoUrl)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->getEtablissementInfoUrl = $getEtablissementInfoUrl;
    }

    /**
     * Retrieves Establishment (ES) information from the Devel Plage InfoService API in XML format.
     *
     * This method sends a GET request to the Devel Plage InfoService API to fetch Establishment information
     * based on the provided Establishment ID (EPI).
     * The Establishment information is returned as an XML document in the response.
     *
     * @param String $epi The Establishment ID (EPI) for which to fetch information.
     *
     * @return SimpleXMLElement|null A SimpleXMLElement object containing the Establishment information in XML format
     *                            or null if an error occurs during the request.
     *
     * @throws Exception If an exception occurs while making the API request, it is caught, and null is returned.
     * @throws TransportExceptionInterface
     */
    public function getESInfoXml(String $epi): ?SimpleXMLElement
    {
        $this->logger->debug('Get ESInfo xml');

        try {
            $response = $this->client->request(
                'GET',
                $this->getEtablissementInfoUrl,
                [
                    'query' => [
                        'ipe' => $epi,
                    ],
                    'timeout' => 3,
                ]
            );

            $xml = simplexml_load_String($response->getContent());
        } catch (Exception) {
            return null;
        }

        return $xml;
    }
}
