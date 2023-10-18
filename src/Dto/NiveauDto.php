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

    public static function fromEntity(Niveau $niveau): self
    {
        return new self(
            $niveau->getId(),
            $niveau->getLibelle()
        );
    }
}