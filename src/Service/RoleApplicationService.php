<?php

namespace App\Service;

use App\Repository\RoleApplicationEntityRepository;
use Psr\Log\LoggerInterface;

class RoleApplicationService
{
    private LoggerInterface $logger;
    private RoleApplicationEntityRepository $roleApplicationEntityRepository;

    public function __construct(
        LoggerInterface $logger,
        RoleApplicationEntityRepository $roleApplicationEntityRepository
    ) {
        $this->logger = $logger;
        $this->roleApplicationEntityRepository = $roleApplicationEntityRepository;
    }

    /**
     * Get Scan Santé roles based on Domain Authorizations and Organization Authorizations.
     *
     * This method takes an array of domain authorizations and an array of organization authorizations
     * and retrieves Scan Santé roles based on the provided perimeters. It searches for roles in the database
     * associated with the domain authorizations and ensures that the corresponding organization authorizations exist.
     * The Scan Santé roles are collected and returned as an array.
     *
     * @param array<int, mixed>|null $habilitationsDomaines An array of domain authorizations.
     * @param array<int, mixed>|null $organisationsHabilitations An array of organization authorizations.
     *
     * @return array<int, mixed> An array containing Scan Santé roles
     *                           based on the domain and organization authorizations.
     */
    public function getRoleScanSante(?array $habilitationsDomaines, ?array $organisationsHabilitations): array
    {
        $this->logger->debug('Get Scan Santé roles');

        $roleScanSante = []; // TODO: set lecteur par default ?

        foreach ($habilitationsDomaines as $habilitationsDomaine) {
            $roleApplications = $this->roleApplicationEntityRepository->findBy(
                ['habilitationDomainePerimetre' => $habilitationsDomaine['perimetre']]
            );

            if (empty($roleApplications)) {
                $this->logger->debug(
                    'No Scan Santé role found for this domain authorization',
                    ['habilitationsDomaine' => $habilitationsDomaine,]
                );
                continue;
            }

            foreach ($roleApplications as $role) {
                $habilitationOrganisationPerimetre = $role->getHabilitationOrganisationPerimetre();

                if (in_array(
                    $habilitationOrganisationPerimetre,
                    array_column($organisationsHabilitations, 'perimetre')
                )) {
                    if (!in_array($role->getRoleApplication(), $roleScanSante)) {
                        $roleScanSante[] = $role->getRoleApplication();
                    }
                }
            }
        }
        return $roleScanSante;
    }
}
