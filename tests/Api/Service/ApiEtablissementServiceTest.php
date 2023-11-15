<?php

namespace App\Tests\Api\Service;

use App\Service\Api\ApiEtablissementService;
use SimpleXMLElement;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Throwable;

class ApiEtablissementServiceTest extends KernelTestCase
{
    public function testGetFinessDomainXml(): void
    {
        $etablissementService = static::getContainer()->get(ApiEtablissementService::class);

        $ipe = '000000001';

        $finessDomainsXml = $etablissementService->getFinessDomainXml($ipe);

        $this->assertNotNull($finessDomainsXml);
        $this->assertInstanceOf(SimpleXMLElement::class, $finessDomainsXml);
    }

    public function testGetFinessDomainXmlFail(): void
    {
        $kernel = self::bootKernel();

        $etablissementService = static::getContainer()->get(ApiEtablissementService::class);

        $ipe = '000000qwe';

        try {
            $finessDomainsXml = $etablissementService->getFinessDomainXml($ipe);
        } catch (Throwable $th) {
            $this->assertNotNull($th);
        }
    }
}
