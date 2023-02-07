<?php

namespace App\Controller\Admin;

use App\Entity\Leave;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LeaveCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Leave::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            // ->setEntityPermission('ADMIN_USER_EDIT')
            //  ->setEntityPermission('ADMIN_USER_SHOW')
            ->showEntityActionsInlined()
            ->setDefaultSort([
                'leave_id' => 'ASC',
            ]);
    }
}
