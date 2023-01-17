<?php

namespace App\Controller\Admin;

use App\Entity\Demand;
use App\Entity\Employee;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class Demand2CrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Demand::class;
    }


    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('employee');

            yield TextField::new('type');

            yield TextField::new('about');

    }


    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityPermission('ADMIN_DEMAND_MANAGE');
    }

}
