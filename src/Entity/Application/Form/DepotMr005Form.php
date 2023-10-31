<?php

namespace App\Entity\Application\Form;

use DateTimeInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class DepotMr005Form
{
    protected string $ipe;
    protected string $finess;
    protected string $raisonSociale;
    protected string $civilite;
    protected string $nom;
    protected string $prenom;
    protected string $fonction;
    protected string $courriel;
    protected string $numeroRecepice;
    protected DateTimeInterface $dateAtribution;
    protected FileType $fileType;

    public function getIpe(): string
    {
        return $this->ipe;
    }

    public function setIpe(string $ipe): self
    {
        $this->ipe = $ipe;

        return $this;
    }

    public function getFiness(): string
    {
        return $this->finess;
    }

    public function setFiness(string $finess): self
    {
        $this->finess = $finess;

        return $this;
    }

    public function getRaisonSociale(): string
    {
        return $this->raisonSociale;
    }

    public function setRaisonSociale(string $raisonSociale): self
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    public function getCivilite(): string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getFonction(): string
    {
        return $this->fonction;
    }

    public function setFonction(string $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getCourriel(): string
    {
        return $this->courriel;
    }

    public function setCourriel(string $courriel): self
    {
        $this->courriel = $courriel;

        return $this;
    }

    public function getNumeroRecepice(): string
    {
        return $this->numeroRecepice;
    }

    public function setNumeroRecepice(string $numeroRecepice): self
    {
        $this->numeroRecepice = $numeroRecepice;

        return $this;
    }

    public function getDateAtribution(): DateTimeInterface
    {
        return $this->dateAtribution;
    }

    public function setDateAtribution(DateTimeInterface $dateAtribution): self
    {
        $this->dateAtribution = $dateAtribution;

        return $this;
    }

    public function getFileType(): FileType
    {
        return $this->fileType;
    }

    public function setFileType(FileType $fileType): self
    {
        $this->fileType = $fileType;

        return $this;
    }
}