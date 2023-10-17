<?php

namespace App\Tests\Repository;

use App\Repository\EtablissementRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EtablissementRepositoryTest extends KernelTestCase
{
    private EtablissementRepository $etablissementRepository;

    private const BAD_EPI = '123';

    protected function setUp(): void
    {
        self::bootKernel();
        $this->etablissementRepository = self::getContainer()->get(etablissementRepository::class);
    }

    public function testGetDevelPlageXml()
    {
        $idUser = getenv('ID_USER');

        $xml = $this->etablissementRepository->getESInfoXml($idUser);

        $this->assertNotNull($xml);
        $this->assertInstanceOf(\SimpleXMLElement::class, $xml);
    }

    public function testGetDevelPlageXmlForBadIpeFormat()
    {
        $epi = '123';
        $xml = $this->etablissementRepository->getESInfoXml($epi);
    
        $this->assertNotNull($xml);
        $this->assertInstanceOf(\SimpleXMLElement::class, $xml);
        $this->assertNotNull($xml->exception);
    }
}
