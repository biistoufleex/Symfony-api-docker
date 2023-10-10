<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\HabilitationsOrganisationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabilitationsOrganisationRepository::class)]
#[ApiResource]
class HabilitationsOrganisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $dateDebut = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dateFin = null;

    #[ORM\Column(length: 255)]
    private ?string $perimetre = null;

    #[ORM\Column(length: 255)]
    private ?string $typeAutorisation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?string
    {
        return $this->dateDebut;
    }

    public function setDateDebut(string $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?string
    {
        return $this->dateFin;
    }

    public function setDateFin(?string $dateFin): static
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
