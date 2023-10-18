<?php

namespace App\Tests\data\fakeObject;

use DateTime;

class FakeHabilitationsOrganisations
{
    private DateTime $dateDebut;
    private DateTime $dateFin;
    private string $perimetre;
    private string $typeAutorisation;

    public function __construct(
        DateTime $dateDebut,
        DateTime $dateFin,
        string $perimetre,
        string $typeAutorisation,
    ) {
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->perimetre = $perimetre;
        $this->typeAutorisation = $typeAutorisation;
    }

    public function getDateDebut(): DateTime
    {
        return $this->dateDebut;
    }

    public function getDateFin(): DateTime
    {
        return $this->dateFin;
    }

    public function getPerimetre(): string
    {
        return $this->perimetre;
    }

    public function getTypeAutorisation(): string
    {
        return $this->typeAutorisation;
    }
}