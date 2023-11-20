<?php

namespace App\Tests\Api\Repository;

use App\Repository\Api\UtilisateurRepository;
use SimpleXMLElement;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UtilisateurRepositoryTest extends KernelTestCase
{
    private UtilisateurRepository $utilisateurRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->utilisateurRepository = self::getContainer()->get(UtilisateurRepository::class);
    }

    public function testGetDevelPlageXml(): void
    {
        $idUser = getenv('ID_USER');

        $xml = $this->utilisateurRepository->getDevelPlageXml($idUser);

        $this->assertNotNull($xml);
        $this->assertInstanceOf(SimpleXMLElement::class, $xml);
    }

    public function testGetDevelPlageXmlForNonExistentUser(): void
    {
        $idUser = 'USER_DOES_NOT_EXIST';

        $xml = $this->utilisateurRepository->getDevelPlageXml($idUser);

        $this->assertNotNull($xml);
        $this->assertInstanceOf(SimpleXMLElement::class, $xml);
        $this->assertNotNull($xml->exception);
    }
}
