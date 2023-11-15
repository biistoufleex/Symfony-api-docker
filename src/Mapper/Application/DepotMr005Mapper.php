<?php

namespace App\Mapper\Application;

use App\Entity\Application\DepotMr005;
use App\Entity\Application\DepotMr005Formulaire;
use DateTime;

class DepotMr005Mapper
{
    public function mapFormDepot(DepotMr005Formulaire $depotMr005Formulaire, string $idPlage): DepotMr005
    {
        $depot = new DepotMr005();
        $depot->setIdPlage($idPlage);
        $depot->setCourriel($depotMr005Formulaire->getCourriel());
        $depot->setIpe($depotMr005Formulaire->getIpe());
        $depot->setFiness($depotMr005Formulaire->getFiness());
        $depot->setRaisonSociale($depotMr005Formulaire->getRaisonSociale());
        $depot->setDateSoumission(new DateTime());
        $depot->setValidated(false);
        $depot->setDepotMr005Formulaire($depotMr005Formulaire);

        return $depot;
    }
}