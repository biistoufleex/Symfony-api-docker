<?php

namespace App\Dto;

class HabilitationsOrganisationDto
{
    public string $dateDebut;

    public ?string $dateFin;

    public string $perimetre;

    public string $typeAutorisation;

    public function __construct(
        string $dateDebut,
        ?string $dateFin,
        string $perimetre,
        string $typeAutorisation
    ) {
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->perimetre = $perimetre;
        $this->typeAutorisation = $typeAutorisation;
    }
}