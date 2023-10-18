<?php

namespace App\Tests\Service;

use App\Service\HabilitationsDomainesService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HabilitationsDomainesServiceTest extends KernelTestCase
{
    public function testGetRoleScanSante(): void
    {
        $habilitationsDomainesService = static::getContainer()->get(HabilitationsDomainesService::class);

        $habilitationDomaines = [
            [
                'perimetre' => 'perimetre1',
            ],
            [
                'perimetre' => 'perimetre2',
            ],
            [
                'perimetre' => 'perimetre3',
            ],
        ];

        $roleScanSante = $habilitationsDomainesService->getRoleScanSante($habilitationDomaines);

        $this->assertNotNull($roleScanSante);
        $this->assertIsArray($roleScanSante);
        $this->assertCount(3, $roleScanSante);
        $this->assertContains('lecteur_perimetre1', $roleScanSante);
        $this->assertContains('lecteur_perimetre2', $roleScanSante);
        $this->assertContains('lecteur_perimetre3', $roleScanSante);
    }
}
