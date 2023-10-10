<?php

namespace App\Dto;

use App\Entity\Utilisateur;

class UtilisateurDto
{
    public int $id;

    public string $nom;

    public string $prenom;

    public string $email;

    public NiveauDTO $niveau;

    public OrganisationDTO $organisation;

    public array $rolesScansante;

    public function __construct(
        int $id,
        string $nom,
        string $prenom,
        string $email,
        NiveauDTO $niveau,
        OrganisationDTO $organisation,
        array $rolesScansante
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->niveau = $niveau;
        $this->organisation = $organisation;
        $this->rolesScansante = $rolesScansante;
    }

    public static function fromEntity(Utilisateur $utilisateur): self
    {
        return new self(
            $utilisateur->getId(),
            $utilisateur->getNom(),
            $utilisateur->getPrenom(),
            $utilisateur->getEmail(),
            NiveauDTO::fromEntity($utilisateur->getNiveau()),
            OrganisationDTO::fromEntity($utilisateur->getOrganisation()),
            $utilisateur->getRolesScansante()
        );
    }
}