<?php

namespace App\Controller\Application\EasyAdmin;

use App\Controller\Application\EasyAdmin\base\AbstractCrudControllerTrait;
use App\Entity\Application\DepotMr005;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class DepotMr005CrudController extends AbstractCrudController
{
    use AbstractCrudControllerTrait;
    public static function getEntityFqcn(): string
    {
        return DepotMr005::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('idPlage'),
            TextField::new('courriel'),
            TextField::new('ipe')->setMaxLength(9),
            TextField::new('finess'),
            TextField::new('raisonSocial'),
            DateField::new('dateSoumission'),
            BooleanField::new('validated')
        ];
    }
}
