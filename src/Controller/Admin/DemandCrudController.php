<?php

namespace App\Controller\Admin;

use App\Entity\Demand;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DemandCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Demand::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
