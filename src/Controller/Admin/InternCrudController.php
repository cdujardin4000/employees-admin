<?php

namespace App\Controller\Admin;

use App\Entity\Intern;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class InternCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Intern::class;
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
