<?php

namespace App\Service;

use App\Repository\RoleApplicationEntityRepository;
use Psr\Log\LoggerInterface;

class RoleApplicationService
{
    private LoggerInterface $logger;
    private RoleApplicationEntityRepository $roleApplicationEntityRepository;

    public function __construct(LoggerInterface $logger, RoleApplicationEntityRepository $roleApplicationEntityRepository)
    {
        $this->logger = $logger;
        $this->roleApplicationEntityRepository = $roleApplicationEntityRepository;
    }

    public function getRoleScanSante(array $habilitationsDomaines, array $organisationsHabilitations): array
    {
        $this->logger->debug('Get Scan Santé roles');

        $roleScanSante = []; // TODO: set lecteur par default ?

        foreach ($habilitationsDomaines as $habilitationsDomaine) {
            $roleApplications = $this->roleApplicationEntityRepository->findBy(['habilitationDomainePerimetre' => $habilitationsDomaine['perimetre']]);

            if (empty($roleApplications)) {
                $this->logger->debug('No Scan Santé role found for this domain authorization', ['habilitationsDomaine' => $habilitationsDomaine,]);
                continue;
            }

            foreach ($roleApplications as $role) {
                $habilitationOrganisationPerimetre = $role->getHabilitationOrganisationPerimetre();

                if (in_array($habilitationOrganisationPerimetre, array_column($organisationsHabilitations, 'perimetre'))) {
                    if (!in_array($role->getRoleApplication(), $roleScanSante)) {
                        $roleScanSante[] = $role->getRoleApplication();
                    }
                }
            }
        }
        return $roleScanSante;
    }
}
