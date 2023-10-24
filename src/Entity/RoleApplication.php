<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RoleApplicationEntityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleApplicationEntityRepository::class)]
#[ApiResource]
class RoleApplication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $roleApplication = null;

    #[ORM\Column(length: 255)]
    private ?string $habilitationOrganisationPerimetre = null;

    #[ORM\Column(length: 255)]
    private ?string $habilitationDomainePerimetre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoleApplication(): ?string
    {
        return $this->roleApplication;
    }

    public function setRoleApplication(string $roleApplication): static
    {
        $this->roleApplication = $roleApplication;

        return $this;
    }

    public function getHabilitationOrganisationPerimetre(): ?string
    {
        return $this->habilitationOrganisationPerimetre;
    }

    public function setHabilitationOrganisationPerimetre(string $habilitationOrganisationPerimetre): static
    {
        $this->habilitationOrganisationPerimetre = $habilitationOrganisationPerimetre;

        return $this;
    }

    public function getHabilitationDomainePerimetre(): ?string
    {
        return $this->habilitationDomainePerimetre;
    }

    public function setHabilitationDomainePerimetre(string $habilitationDomainePerimetre): static
    {
        $this->habilitationDomainePerimetre = $habilitationDomainePerimetre;

        return $this;
    }
}
