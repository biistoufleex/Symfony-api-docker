<?php

namespace App\Mapper;

use Psr\Log\LoggerInterface;
use DateTime;

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
     * @return Array An associative Array containing formatted Establishment information.
     */
    public function formatESInfoXml($xml): array
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
                if ($finessDomaine && Array_key_exists((string) $finessDomaine->domaine->libelle, $domainesPerimetres)) {

                    // récupère tous les domaines présents qui ont une dateFin à null
                    if (empty($finessDomaine->dateFin)) {
                        $habilitationsDomaines[] = [
                            'date_debut' => !empty($finessDomaine->dateDebut) ?
                                (new DateTime((string) $finessDomaine->dateDebut))->format('d/m/Y') : null,

                            'date_fin' => !empty($finessDomaine->dateFin) ?
                                (new DateTime((string) $finessDomaine->dateFin))->format('d/m/Y') : null,

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
