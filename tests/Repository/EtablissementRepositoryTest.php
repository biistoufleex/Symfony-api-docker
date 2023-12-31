<?php

namespace App\Tests\Repository;

use App\Repository\EtablissementRepository;
use SimpleXMLElement;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EtablissementRepositoryTest extends KernelTestCase
{
    private EtablissementRepository $etablissementRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->etablissementRepository = self::getContainer()->get(etablissementRepository::class);
    }

    public function testGetDevelPlageXml(): void
    {
        $idUser = getenv('ID_USER');

        $xml = $this->etablissementRepository->getESInfoXml($idUser);

        $this->assertNotNull($xml);
        $this->assertInstanceOf(SimpleXMLElement::class, $xml);
    }

    public function testGetDevelPlageXmlForBadIpeFormat(): void
    {
        $epi = '123';
        $xml = $this->etablissementRepository->getESInfoXml($epi);
    
        $this->assertNotNull($xml);
        $this->assertInstanceOf(SimpleXMLElement::class, $xml);
        $this->assertNotNull($xml->exception);
    }
}
