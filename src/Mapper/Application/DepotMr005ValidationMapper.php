<?php

namespace App\Mapper\Application;

use App\Entity\Application\DepotMr005Validation;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DepotMr005ValidationMapper
{
    public function map(array $data, UploadedFile $uploadedFile): DepotMr005Validation
    {
        return new DepotMr005Validation($data, $uploadedFile);
    }
}