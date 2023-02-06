<?php

namespace App\Controller\Admin;

use App\Entity\Car;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CarCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Car::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            // ->setEntityPermission('ADMIN_USER_EDIT')
            //  ->setEntityPermission('ADMIN_USER_SHOW')
            ->showEntityActionsInlined()
            ->setDefaultSort([
                'car_id' => 'ASC',
            ]);
    }
}
