<?php

namespace App\Mapper\Application;

use App\Entity\Application\DepotMr005Formulaire;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DepotMr005FormulaireMapper
{
    /**
     * @param array<string>|array<array<string>> $data
     * @param UploadedFile $uploadedFile
     * @return DepotMr005Formulaire
     */
    public function map(array $data, UploadedFile $uploadedFile): DepotMr005Formulaire
    {
        return new DepotMr005Formulaire($data, $uploadedFile);
    }
}