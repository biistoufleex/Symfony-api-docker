<?php

namespace App\Repository;

use Psr\Log\LoggerInterface;
use SimpleXMLElement;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class EtablissementRepository
{
    private HttpClientInterface $client;
    private LoggerInterface $logger;

    public function __construct(HttpClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * Retrieves Establishment (ES) information from the Devel Plage InfoService API in XML format.
     *
     * This method sends a GET request to the Devel Plage InfoService API to fetch Establishment information
     * based on the provided Establishment ID (EPI). The Establishment information is returned as an XML document in the response.
     *
     * @param String $epi The Establishment ID (EPI) for which to fetch information.
     *
     * @return SimpleXMLElement|null A SimpleXMLElement object containing the Establishment information in XML format
     *                            or null if an error occurs during the request.
     *
     * @throws \Exception If an exception occurs while making the API request, it is caught, and null is returned.
     */
    public function getESInfoXml(String $epi): ?SimpleXMLElement
    {
        $this->logger->debug('Get ESInfo xml');

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

            $xml = simplexml_load_String($response->getContent());
        } catch (\Exception $e) {
            return null;
        }

        return $xml;
    }
}
