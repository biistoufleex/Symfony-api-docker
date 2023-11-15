<?php

namespace App\Tests\Api\Service;

use App\Service\Common\UtilisateurService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UtilisateurServiceTest extends KernelTestCase
{
    public function testGetDevelXml(): void
    {
        $utilisateurService = static::getContainer()->get(UtilisateurService::class);

        $plageXml = $utilisateurService->getDevelXml('17441');

        $this->assertNotNull($plageXml);
        $this->assertEquals('17441', $plageXml->id);
    }

    public function testGetDevelXmlNoAccount(): void
    {
        $utilisateurService = static::getContainer()->get(UtilisateurService::class);

        try {
            $plageXml = $utilisateurService->getDevelXml('123456789');
        } catch (Exception $e) {
            $this->assertEquals('pas de compte trouvé', $e->getMessage());
        }
    }
}
