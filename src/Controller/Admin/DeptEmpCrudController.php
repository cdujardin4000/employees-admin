<?php

namespace App\Controller\Admin;

use App\Entity\DeptEmp;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DeptEmpCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DeptEmp::class;
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
