<?php

namespace App\Controller\Admin;

use App\Entity\Mission;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MissionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Mission::class;
    }

    /*    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
        {
            $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
            if ($this->isGranted('ROLE_MANAGER') || $this->isGranted('ROLE_SUPER_ADMIN'))
            {

            }
        }*/


    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('mission_id')
            ->onlyOnIndex();

        yield IntegerField::new('id')
            ->hideWhenCreating();

        yield TextField::new('description')
            ->setMaxLength(255);

        yield DateField::new('due_date');

        yield TextField::new('status');

    }


    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->showEntityActionsInlined();

    }
}