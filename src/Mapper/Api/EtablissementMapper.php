<?php

namespace App\Mapper\Api;

use DateTime;
use Exception;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;

class EtablissementMapper
{

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Formats Establishment (ES) information from an XML response into an Array.
     *
     * This method takes an XML object as input and extracts relevant Establishment information from it.
     * The extracted information is then organized into an associative Array and returned.
     *
     * @param SimpleXMLElement $xml The XML object containing Establishment information.
     *
     * @return array<int, mixed> An associative Array containing formatted Establishment information.
     * @throws Exception
     */
    public function formatESInfoXml(SimpleXMLElement $xml): array
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
                if ($finessDomaine && Array_key_exists(
                    (string) $finessDomaine->domaine->libelle,
                    $domainesPerimetres
                )) {
                    // récupère tous les domaines présents qui ont une dateFin à null
                    if (empty($finessDomaine->dateFin)) {
                        $habilitationsDomaines[] = [
                            'date_debut' => !empty($finessDomaine->dateDebut) ?
                                (new DateTime((string) $finessDomaine->dateDebut))->format('d/m/Y') : null,
                            'date_fin' => null,
                            'perimetre' => $domainesPerimetres[(string) $finessDomaine->domaine->libelle],
                            'type_autorisation' => 'Domaine',
                        ];
                    }
                }
            }
        }

        return $habilitationsDomaines;
    }
}
