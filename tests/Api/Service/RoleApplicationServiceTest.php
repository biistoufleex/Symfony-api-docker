<?php

namespace App\Tests\Api\Service;

use App\Service\Api\RoleApplicationService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RoleApplicationServiceTest extends KernelTestCase
{
    public function testGetRoleScanSanteFound(): void
    {
        $roleApplicationService = static::getContainer()->get(RoleApplicationService::class);

        $habilitationsDomaines = [["perimetre" => "Médico-Social"], ["perimetre" => "Finance"], ["perimetre" => "Test"]];
        $organisationsHabilitations = [["perimetre" => "Institution"], ["perimetre" => "Finance"], ["perimetre" => "Test"]];

        $roleScanSante = $roleApplicationService->getRoleScanSante($habilitationsDomaines, $organisationsHabilitations);

        $this->assertNotEmpty($roleScanSante);
        $this->assertCount(2, $roleScanSante);
        $this->assertContains('Lecteur Fincances', $roleScanSante);
        $this->assertContains('Lecteur Institution', $roleScanSante);
    }

    public function testGetRoleScanSanteNotFound(): void
    {
        $roleApplicationService = static::getContainer()->get(RoleApplicationService::class);

        $habilitationsDomaines = [["perimetre" => "Médico-Social"], ["perimetre" => "Finance"], ["perimetre" => "Test"]];
        $organisationsHabilitations = [["perimetre" => "QWE"], ["perimetre" => "ASD"], ["perimetre" => "ZXC"]];

        $roleScanSante = $roleApplicationService->getRoleScanSante($habilitationsDomaines, $organisationsHabilitations);

        $this->assertEmpty($roleScanSante);
    }
}
