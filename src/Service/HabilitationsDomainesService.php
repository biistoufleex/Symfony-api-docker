<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class HabilitationsDomainesService
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Get Scan Santé Roles based on Domain Authorizations.
     *
     * This method takes an array of domain authorizations and generates Scan Santé roles
     * based on the given perimeters. It constructs an array of Scan Santé roles prefixed with "lecteur_"
     * for each unique perimeter found in the domain authorizations.
     *
     * @param array $habilitationDomaines An array of domain authorizations.
     *
     * @return array|null An array containing Scan Santé roles based on domain authorizations.
     */
    public function getRoleScanSante(array $habilitationDomaines): ?array
    {
        $roleScanSante = [];

        foreach ($habilitationDomaines as $habilitationDomaine) {
            if (!in_array("lecteur_" . $habilitationDomaine['perimetre'], $roleScanSante)) {
                $roleScanSante[] = "lecteur_" . $habilitationDomaine['perimetre'];
            }
        }

        return $roleScanSante;
    }
}
