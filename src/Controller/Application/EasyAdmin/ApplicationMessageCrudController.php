<?php

namespace App\Controller\Application\EasyAdmin;

use App\Controller\Application\EasyAdmin\base\AbstractCrudControllerTrait;
use App\Entity\Application\ApplicationMessage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ApplicationMessageCrudController extends AbstractCrudController
{
    use AbstractCrudControllerTrait;
    public static function getEntityFqcn(): string
    {
        return ApplicationMessage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            Field::new('id')->onlyOnIndex(),
            TextField::new('usecase')->setMaxLength(255),
            TextField::new('uri')->setMaxLength(255),
            TextEditorField::new('message'),
        ];
    }
}
