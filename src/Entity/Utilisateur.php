<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\This;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ApiResource]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\ManyToOne(inversedBy: 'utilisateurs')]
    private ?Niveau $niveau = null;

    #[ORM\ManyToOne(inversedBy: 'utilisateurs')]
    private ?Organisation $Organisation = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @var array<string, mixed>
     */
    #[ORM\Column(type: 'Json_array')]
    private array $rolesScansante = [];

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveau $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getOrganisation(): ?Organisation
    {
        return $this->Organisation;
    }

    public function setOrganisation(?Organisation $Organisation): static
    {
        $this->Organisation = $Organisation;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRolesScansante(): array
    {
        return $this->rolesScansante;
    }

    /**
     * @param array<string, mixed> $rolesScansante
     * @return $this
     */
    public function setRolesScansante(array $rolesScansante): static
    {
        $this->rolesScansante = $rolesScansante;

        return $this;
    }
}
