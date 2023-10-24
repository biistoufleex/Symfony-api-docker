<?php

namespace App\Tests\Service;

use App\Service\RoleApplicationService;
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
        $this->assertCount(3, $roleScanSante);
        $this->assertContains('Lecteur Institution Médico-Social', $roleScanSante);
        $this->assertContains('Lecteur institution Finance', $roleScanSante);
        $this->assertContains('Lecteur Test', $roleScanSante);
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
