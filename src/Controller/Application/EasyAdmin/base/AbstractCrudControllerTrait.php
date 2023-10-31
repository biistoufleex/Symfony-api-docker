<?php

namespace App\Controller\Application\EasyAdmin\base;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

trait AbstractCrudControllerTrait
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
//            ->setEntityPermission('ROLE_ADMIN') // TODO: set permission
            ;
    }
}