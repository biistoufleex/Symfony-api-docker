<?php

namespace App\Dto;

use App\Entity\Niveau;

class NiveauDto
{
    public int $id;

    public string $libelle;

    public function __construct(int $id, string $libelle)
    {
        $this->id = $id;
        $this->libelle = $libelle;
    }
}