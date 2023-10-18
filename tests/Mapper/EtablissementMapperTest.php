<?php

namespace App\Tests\Mapper;

use App\Mapper\EtablissementMapper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EtablissementMapperTest extends KernelTestCase
{
    private EtablissementMapper $etablissementMapper;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->etablissementMapper = self::getContainer()->get(EtablissementMapper::class);
    }

    public function testMapToUtilisateurDto(): void
    {
        $xml = simplexml_load_file(__DIR__ . '/../data/infoservice-etablissementInfo.xml');

        $result = $this->etablissementMapper->formatESInfoXml($xml);

        $this->assertEquals([
            [
                'date_debut' => '02/01/2010',
                'date_fin' => null,
                'perimetre' => 'Médico-Social',
                'type_autorisation' => 'Domaine',
            ],
            [
                'date_debut' => '02/01/2022',
                'date_fin' => null,
                'perimetre' => 'RH',
                'type_autorisation' => 'Domaine',
            ],
            [
                'date_debut' => '01/01/2011',
                'date_fin' => null,
                'perimetre' => 'Finances',
                'type_autorisation' => 'Domaine',
            ],
        ], $result);
    }
}
