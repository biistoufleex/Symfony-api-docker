<?php

namespace App\Dto\Api;

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