<?php

namespace App\Dto;

class UtilisateurDto
{
    public int $id;

    public string $nom;

    public string $prenom;

    public string $email;

    public NiveauDto $niveau;

    public OrganisationDto $organisation;

    /**
     * @var array<int, mixed>
     */
    public array $rolesScansante;

    /**
     * @param array<int, mixed> $rolesScansante
     */
    public function __construct(
        int $id,
        string $nom,
        string $prenom,
        string $email,
        NiveauDto $niveau,
        OrganisationDto $organisation,
        array $rolesScansante,
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->niveau = $niveau;
        $this->organisation = $organisation;
        $this->rolesScansante = $rolesScansante;
    }
}
