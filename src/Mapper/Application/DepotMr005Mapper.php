<?php

namespace App\Mapper\Application;

use App\Entity\Application\DepotMr005;
use App\Entity\Application\DepotMr005Validation;
use DateTime;

class DepotMr005Mapper
{
    public function mapFormDepot(DepotMr005Validation $depotMr005Validation, string $idPlage): DepotMr005
    {
        $depot = new DepotMr005();
        $depot->setIdPlage($idPlage);
        $depot->setCourriel($depotMr005Validation->getCourriel());
        $depot->setIpe($depotMr005Validation->getIpe());
        $depot->setFiness($depotMr005Validation->getFiness());
        $depot->setRaisonSocial($depotMr005Validation->getRaisonSocial());
        $depot->setDateSoumission(new DateTime());
        $depot->setValidated(false);
        $depot->setDepotMr005Validation($depotMr005Validation);

        return $depot;
    }
}