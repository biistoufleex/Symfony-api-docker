<?php

namespace App\Controller\Http\Responses;

use App\Dto\UtilisateurDto;

class HabilitationReponse
{
    public ?UtilisateurDto $info_utilisateur = null;
    public ?array $habilitations_organisation = null;
    public ?array $habilitations_domaines = null;
    public ?array $habilitations_scansante = null;

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

    public function getHabilitationsOrganisation(): array
    {
        return $this->habilitations_organisation;
    }

    public function setHabilitationsOrganisation(array $habilitationsOrganisation): self
    {
        $this->habilitations_organisation = $habilitationsOrganisation;

        return $this;
    }

    public function getHabilitationsDomaines(): array
    {
        return $this->habilitations_domaines;
    }

    public function setHabilitationsDomaines(array $habilitationsDomaines): self
    {
        $this->habilitations_domaines = $habilitationsDomaines;

        return $this;
    }

    public function getHabilitationsScansante(): array
    {
        return $this->habilitations_scansante;
    }

    public function setHabilitationsScansante(array $habilitationsScansante): self
    {
        $this->habilitations_scansante = $habilitationsScansante;

        return $this;
    }

    public function toArray(): array
    {
        return [
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
}