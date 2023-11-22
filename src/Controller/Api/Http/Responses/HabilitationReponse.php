<?php

namespace App\Controller\Api\Http\Responses;

use App\Dto\Api\UtilisateurDto;
use Symfony\Component\Security\Core\User\UserInterface;

class HabilitationReponse implements UserInterface
{
    private array $roles = [];

    /**
     * @var array<string, mixed>
     */
    private array $retour = [];
    private ?UtilisateurDto $info_utilisateur = null;

    /**
     * @var array<int, mixed>|null
     */
    private ?array $habilitations_organisation = null;

    /**
     * @var array<int, mixed>|null
     */
    private ?array $habilitations_domaines = null;

    /**
     * @var array<int, mixed>|null
     */
    private ?array $habilitations_scansante = null;

    public function __construct()
    {
    }

    public function getInfoUtilisateur(): UtilisateurDto
    {
        return $this->info_utilisateur;
    }

    public function setInfoUtilisateur(UtilisateurDto $infoUtilisateur): self
    {
        $this->info_utilisateur = $infoUtilisateur;

        return $this;
    }

    /**
     * @return array<int, mixed>|null
     */
    public function getHabilitationsOrganisation(): ?array
    {
        return $this->habilitations_organisation;
    }

    /**
     * @param array<int, mixed> $habilitationsOrganisation
     * @return self
     */
    public function setHabilitationsOrganisation(array $habilitationsOrganisation): self
    {
        $this->habilitations_organisation = $habilitationsOrganisation;

        return $this;
    }

    /**
     * @return array<int, mixed>|null
     */
    public function getHabilitationsDomaines(): ?array
    {
        return $this->habilitations_domaines;
    }

    /**
     * @param array<int, mixed> $habilitationsDomaines
     * @return self
     */
    public function setHabilitationsDomaines(array $habilitationsDomaines): self
    {
        $this->habilitations_domaines = $habilitationsDomaines;

        return $this;
    }

    /**
     * @return array<int, mixed>|null
     */
    public function getHabilitationsScansante(): ?array
    {
        return $this->habilitations_scansante;
    }

    /**
     * @param array<int, mixed>|null $habilitationsScansante
     * @return self
     */
    public function setHabilitationsScansante(?array $habilitationsScansante): self
    {
        $this->habilitations_scansante = $habilitationsScansante;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRetour(): array
    {
        return $this->retour;
    }

    /**
     * @param array<string, mixed> $retour
     * @return self
     */
    public function setRetour(array $retour): self
    {
        $this->retour = $retour;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'retour' => $this->retour,
            'info_utilisateur' => $this->info_utilisateur,
            'habilitations_organisation' => $this->habilitations_organisation,
            'habilitations_domaines' => $this->habilitations_domaines,
            'habilitations_scansante' => $this->habilitations_scansante,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->info_utilisateur->id;
    }
}
