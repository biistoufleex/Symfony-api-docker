<?php

namespace App\Dto;

use App\Entity\Organisation;

class OrganisationDto
{
    public string $id;

    public string $libelle;

    public function __construct(string $id, string $libelle)
    {
        $this->id = $id;
        $this->libelle = $libelle;
    }

    public static function fromEntity(Organisation $organisation): self
    {
        return new self(
            $organisation->getId(),
            $organisation->getLibelle()
        );
    }
}