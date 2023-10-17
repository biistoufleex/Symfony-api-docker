<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\OrganisationAutorisationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrganisationAutorisationRepository::class)]
#[ApiResource]
class OrganisationAutorisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $identifiantOrganisationPlage = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(length: 50)]
    private ?string $perimetre = null;

    #[ORM\Column(length: 50)]
    private ?string $typeAutorisation = null;

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifiantOrganisationPlage(): ?string
    {
        return $this->identifiantOrganisationPlage;
    }

    public function setIdentifiantOrganisationPlage(string $identifiantOrganisationPlage): static
    {
        $this->identifiantOrganisationPlage = $identifiantOrganisationPlage;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getPerimetre(): ?string
    {
        return $this->perimetre;
    }

    public function setPerimetre(string $perimetre): static
    {
        $this->perimetre = $perimetre;

        return $this;
    }

    public function getTypeAutorisation(): ?string
    {
        return $this->typeAutorisation;
    }

    public function setTypeAutorisation(string $typeAutorisation): static
    {
        $this->typeAutorisation = $typeAutorisation;

        return $this;
    }
}
