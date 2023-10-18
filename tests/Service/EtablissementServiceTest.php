<?php

namespace App\Tests\Service;

use App\Service\EtablissementService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EtablissementServiceTest extends KernelTestCase
{
    public function testGetFinessDomainXml(): void
    {
        $etablissementService = static::getContainer()->get(EtablissementService::class);

        $ipe = '000000001';

        $finessDomainsXml = $etablissementService->getFinessDomainXml($ipe);

        $this->assertNotNull($finessDomainsXml);
        $this->assertInstanceOf(\SimpleXMLElement::class, $finessDomainsXml);
    }

    public function testGetFinessDomainXmlFail(): void
    {
        $kernel = self::bootKernel();

        $etablissementService = static::getContainer()->get(EtablissementService::class);

        $ipe = '000000qwe';

        try {
            $finessDomainsXml = $etablissementService->getFinessDomainXml($ipe);
        } catch (\Throwable $th) {
            $this->assertNotNull($th);
        }
    }
}
