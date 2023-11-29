<?php

namespace App\Mapper\Application;

use App\Entity\Application\DepotMr005Formulaire;
use App\Form\Entity\DepotMr005Form;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DepotMr005FormulaireMapper
{
    private const DATE_ATTRIBUTION = 'dateAttribution';
    private const DEPOT_MR_005 = "depot_mr005";

    /**
     * @param array<string>|array<array<string>> $data
     * @param UploadedFile $uploadedFile
     * @return DepotMr005Formulaire
     */
    public function map(array $data, UploadedFile $uploadedFile): DepotMr005Formulaire
    {
        return new DepotMr005Formulaire($data, $uploadedFile);
    }

    /**
     * @param DepotMr005Formulaire $depotMr005Formulaire
     * @param string|null $localFilePath
     * @return DepotMr005Form
     * @throws Exception
     */
    public function toDepotForm(DepotMr005Formulaire $depotMr005Formulaire, string $localFilePath = null): DepotMr005Form
    {
        $depotMr005Form = new DepotMr005Form();
        $depotMr005Form->setIpe($depotMr005Formulaire->getIpe());
        $depotMr005Form->setFiness($depotMr005Formulaire->getFiness());
        $depotMr005Form->setRaisonSociale($depotMr005Formulaire->getRaisonSociale());
        $depotMr005Form->setCivilite($depotMr005Formulaire->getCivilite());
        $depotMr005Form->setNom($depotMr005Formulaire->getNom());
        $depotMr005Form->setPrenom($depotMr005Formulaire->getPrenom());
        $depotMr005Form->setFonction($depotMr005Formulaire->getFonction());
        $depotMr005Form->setCourriel($depotMr005Formulaire->getCourriel());
        $depotMr005Form->setNumeroRecepice($depotMr005Formulaire->getNumeroRecepice());
        $dateAttribution = new DateTime($depotMr005Formulaire->getDateAttribution());
        $depotMr005Form->setDateAttribution($dateAttribution);

        return $depotMr005Form;
    }

    /**
     * @param DepotMr005Formulaire $depotMr005Formulaire
     * @param array<string> $data
     * @param array<string> $file
     * @return DepotMr005Formulaire
     * @throws Exception
     */
    public function update(DepotMr005Formulaire $depotMr005Formulaire, array $data, array $file): DepotMr005Formulaire
    {
        $depotMr005Formulaire->setCivilite($data[self::DEPOT_MR_005]["civilite"]);
        $depotMr005Formulaire->setNom($data[self::DEPOT_MR_005]["nom"]);
        $depotMr005Formulaire->setPrenom($data[self::DEPOT_MR_005]["prenom"]);
        $depotMr005Formulaire->setFonction($data[self::DEPOT_MR_005]["fonction"]);
        $depotMr005Formulaire->setCourriel($data[self::DEPOT_MR_005]["courriel"]);
        $depotMr005Formulaire->setNumeroRecepice($data[self::DEPOT_MR_005]["numeroRecepice"]);
        $dateString = $data[self::DEPOT_MR_005][self::DATE_ATTRIBUTION]['year']
            . '-' . $data[self::DEPOT_MR_005][self::DATE_ATTRIBUTION]['month']
            . '-' . $data[self::DEPOT_MR_005][self::DATE_ATTRIBUTION]['day'];
        $dateString .= ' ' . date('H:i:s');
        $depotMr005Formulaire->setDateAttribution($dateString);
        if ($file[self::DEPOT_MR_005]['filePath'])
            /** @var UploadedFile $file */
            $depotMr005Formulaire->setFilePath($file[self::DEPOT_MR_005]['filePath']->getClientOriginalName());

        return $depotMr005Formulaire;
    }
}