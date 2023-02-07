<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            // ->setEntityPermission('ADMIN_USER_EDIT')
            //  ->setEntityPermission('ADMIN_USER_SHOW')
            ->showEntityActionsInlined()
            ->setDefaultSort([
                'project_id' => 'ASC',
            ]);
    }
}
