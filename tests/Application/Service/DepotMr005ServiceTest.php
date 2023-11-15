<?php

namespace App\Tests\Application\Service;

use App\Service\Application\DepotMr005Service;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DepotMr005ServiceTest extends KernelTestCase
{

    public function testGetRecepiceByIpe(): void
    {
        $depotMr005Service = static::getContainer()->get(DepotMr005Service::class);

        $ipe = 'QWERTYUIO';

        $recepices = $depotMr005Service->getRecepiceByIpe($ipe);

        $this->assertNotNull($recepices);
        $this->assertCount(1, $recepices);
        $this->assertEquals($ipe, $recepices[0]->getIpe());
    }

    public function testGetRecepiceByFiness(): void
    {
        $depotMr005Service = static::getContainer()->get(DepotMr005Service::class);

        $finess = 'QWERTYUIO';

        $recepices = $depotMr005Service->getRecepiceByFiness($finess);

        $this->assertNotNull($recepices);
        $this->assertCount(1, $recepices);
        $this->assertEquals($finess, $recepices[0]->getIpe());
    }
}