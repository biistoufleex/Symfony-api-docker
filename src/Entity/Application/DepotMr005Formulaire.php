<?php

namespace App\Entity\Application;

use App\Repository\Application\DepotMr005FormulaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: DepotMr005FormulaireRepository::class)]
class DepotMr005Formulaire
{
    private const DATE_ATTRIBUTION = 'dateAttribution';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 9)]
    private ?string $ipe = null;

    #[ORM\Column(length: 9)]
    private ?string $finess = null;

    #[ORM\Column(length: 100)]
    private ?string $raisonSociale = null;

    #[ORM\Column(length: 15)]
    private ?string $civilite = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $fonction = null;

    #[ORM\Column(length: 255)]
    private ?string $courriel = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $numeroRecepice = null;

    #[ORM\Column(length: 255)]
    private ?string $dateAttribution = null;

    #[ORM\Column(length: 255)]
    private ?string $filePath = null;

    #[ORM\OneToOne(mappedBy: 'depotMr005Formulaire', cascade: ['persist', 'remove'])]
    private ?DepotMr005 $depotMr005 = null;

    /**
     * @param array<string>|array<array<string>> $formData
     * @param UploadedFile $uploadedFile
     */
    public function  __construct(array $formData, UploadedFile $uploadedFile)
    {
        $this->ipe = $formData['ipe'];
        $this->finess = $formData['finess'];
        $this->raisonSociale = $formData['raisonSociale'];
        $this->civilite = $formData['civilite'];
        $this->nom = $formData['nom'];
        $this->prenom = $formData['prenom'];
        $this->fonction = $formData['fonction'];
        $this->courriel = $formData['courriel'];
        $this->numeroRecepice = $formData['numeroRecepice'];

        # ajout de l'heure dans la date
        $dateString = $formData[self::DATE_ATTRIBUTION]['year']
            . '-' . $formData[self::DATE_ATTRIBUTION]['month']
            . '-' . $formData[self::DATE_ATTRIBUTION]['day'];
        $dateString .= ' ' . date('H:i:s');
        $this->dateAttribution = $dateString;

        $this->filePath = $uploadedFile->getClientOriginalName();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): static
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(string $fonction): static
    {
        $this->fonction = $fonction;

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

    public function getNumeroRecepice(): ?string
    {
        return $this->numeroRecepice;
    }

    public function setNumeroRecepice(string $numeroRecepice): static
    {
        $this->numeroRecepice = $numeroRecepice;

        return $this;
    }

    public function getDateAttribution(): ?string
    {
        return $this->dateAttribution;
    }

    public function setDateAttribution(string $dateAttribution): static
    {
        $this->dateAttribution = $dateAttribution;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(string $filePath): static
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getDepotMr005(): ?DepotMr005
    {
        return $this->depotMr005;
    }

    public function setDepotMr005(?DepotMr005 $depotMr005): static
    {
        // unset the owning side of the relation if necessary
        if ($depotMr005 === null && $this->depotMr005 !== null) {
            $this->depotMr005->setDepotMr005Formulaire(null);
        }

        // set the owning side of the relation if necessary
        if ($depotMr005 !== null && $depotMr005->getDepotMr005Formulaire() !== $this) {
            $depotMr005->setDepotMr005Formulaire($this);
        }

        $this->depotMr005 = $depotMr005;

        return $this;
    }
}
