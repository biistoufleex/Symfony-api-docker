<?php

namespace App\Dto\Api;

class OrganisationDto
{
    public string $id;

    public string $libelle;

    public function __construct(string $id, string $libelle)
    {
        $this->id = $id;
        $this->libelle = $libelle;
    }
}