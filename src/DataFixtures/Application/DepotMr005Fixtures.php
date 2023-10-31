<?php

namespace App\DataFixtures\Application;

use App\Entity\Application\DepotMr005;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

class DepotMr005Fixtures extends Fixture
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $data = [
            ['idPlage' => 'QWERTYUIO', 'courriel' => 'billy@mail.fr',
                'ipe' => 'QWERTYUIO', 'finess' => 'QWERTYUIO',
                'raisonSocial' => 'Hopital de Paris',
                'dateSoumission' => '2021-01-01'
            ],
            ['idPlage' => 'ASDFHJKL', 'courriel' => 'bob@mail.fr',
                'ipe' => 'ASDFHJKL', 'finess' => 'ASDFHJKL',
                'raisonSocial' => 'Hopital de Marseille',
                'dateSoumission' => '2022-01-13'
            ],
        ];

        foreach ($data as $row) {
            $recepice = new DepotMr005();
            $recepice->setIdPlage($row['idPlage']);
            $recepice->setCourriel($row['courriel']);
            $recepice->setIpe($row['ipe']);
            $recepice->setFiness($row['finess']);
            $recepice->setRaisonSocial($row['raisonSocial']);
            $recepice->setDateSoumission(new DateTime($row['dateSoumission']));
            $manager->persist($recepice);
        }
        $manager->flush();
    }
}