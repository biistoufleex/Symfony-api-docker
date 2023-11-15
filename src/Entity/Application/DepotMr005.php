<?php

namespace App\Entity\Application;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\Application\DepotMr005Repository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DepotMr005Repository::class)]
#[ApiResource]
class DepotMr005
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(max: 50)]
    private ?string $idPlage = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(max: 100)]
    #[Assert\Email]
    private ?string $courriel = null;

    #[ORM\Column(length: 9)]
    #[Assert\Length(min: 9, max: 9)]
    private ?string $ipe = null;

    #[ORM\Column(length: 9)]
    #[Assert\Length(min: 9, max: 9)]
    private ?string $finess = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(max: 100)]
    private ?string $raisonSociale = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $dateSoumission = null;

    #[ORM\Column]
    private ?bool $validated = false;

    #[ORM\OneToOne(inversedBy: 'depotMr005', cascade: ['persist', 'remove'])]
    private ?DepotMr005Formulaire $depotMr005Formulaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPlage(): ?string
    {
        return $this->idPlage;
    }

    public function setIdPlage(string $idPlage): static
    {
        $this->idPlage = $idPlage;

        return $this;
    }

    public function getCourriel(): ?string
    {
        return $this->courriel;
    }

    public function setCourriel(string $courriel): static
    {
        $this->courriel = $courriel;

        return $this;
    }

    public function getIpe(): ?string
    {
        return $this->ipe;
    }

    public function setIpe(string $ipe): static
    {
        $this->ipe = $ipe;

        return $this;
    }

    public function getFiness(): ?string
    {
        return $this->finess;
    }

    public function setFiness(string $finess): static
    {
        $this->finess = $finess;

        return $this;
    }

    public function getRaisonSociale(): ?string
    {
        return $this->raisonSociale;
    }

    public function setRaisonSociale(string $raisonSociale): static
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    public function getDateSoumission(): ?DateTimeInterface
    {
        return $this->dateSoumission;
    }

    public function setDateSoumission(DateTimeInterface $dateSoumission): static
    {
        $this->dateSoumission = $dateSoumission;

        return $this;
    }

    public function isValidated(): ?bool
    {
        return $this->validated;
    }

    public function setValidated(bool $validated): static
    {
        $this->validated = $validated;

        return $this;
    }

    public function getDepotMr005Formulaire(): ?DepotMr005Formulaire
    {
        return $this->depotMr005Formulaire;
    }

    public function setDepotMr005Formulaire(?DepotMr005Formulaire $depotMr005Formulaire): static
    {
        $this->depotMr005Formulaire = $depotMr005Formulaire;

        return $this;
    }
}
