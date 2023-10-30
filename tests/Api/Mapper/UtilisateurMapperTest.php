<?php

namespace App\Tests\Api\Mapper;

use App\Mapper\Api\UtilisateurMapper;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UtilisateurMapperTest extends KernelTestCase
{
    private UtilisateurMapper $utilisateurMapper;

    protected function setUp(): void
    {
        self::bootKernel();
        try {
            $this->utilisateurMapper = self::getContainer()->get(UtilisateurMapper::class);
        } catch (Exception) {
        }
    }

    public function testMapToUtilisateurDto():void
    {
        $xml = simplexml_load_file(__DIR__ . '/../data/infoService-userInfo.xml');

        $utilisateurDto = $this->utilisateurMapper->formatInfoUserXml($xml);

        $this->assertEquals('12345', $utilisateurDto['id']);
        $this->assertEquals('Doe', $utilisateurDto['nom']);
        $this->assertEquals('John', $utilisateurDto['prenom']);
        $this->assertEquals('john.doe@example.com', $utilisateurDto['email']);
        $this->assertEquals('2', $utilisateurDto['niveau']['id']);
        $this->assertEquals('Utilisateur', $utilisateurDto['niveau']['libelle']);
        $this->assertEquals('XYZCorp', $utilisateurDto['organisation']['id']);
        $this->assertEquals('XYZ Corporation', $utilisateurDto['organisation']['libelle']);
        $this->assertEquals(['Administrateur', 'Gestionnaire FINESS', 'Super Utilisateur'], $utilisateurDto['roles_scansante']);
    }
}
