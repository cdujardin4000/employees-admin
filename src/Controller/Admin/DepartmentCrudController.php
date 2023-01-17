<?php

namespace App\Controller\Admin;

use App\Entity\Department;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DepartmentCrudController extends AbstractCrudController
{
    public const DEPARTMENT_BASE_PATH = 'uploads/departments/';
    public const DEPARTMENT_UPLOAD_DIR = 'public/uploads/departments/';

    public static function getEntityFqcn(): string
    {
        return Department::class;
    }


    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if(!$entityInstance instanceof Department) {
            return;
        }

        parent::persistEntity($entityManager, $entityInstance); // TODO: Change the autogenerated stub

    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('dept_no')
            ->onlyOnIndex();

        yield TextField::new('dept_name');

        yield TextAreaField::new('description')
            ->hideOnIndex()
            ->setFormTypeOptions([
                'row_attr' => [
                    'data-controller' => 'markdown',
                ],
                'attr' => [
                    'data-markdown-target' => 'input',
                    'data-action' => 'markdown#render',
                ],
            ])
            ->setHelp('Preview:');

        yield ImageField::new('dept_img')
            ->formatValue(static function ($value, ?Department $dept) {
                return $dept?->getDeptImg();
            })
            ->setBasePath(self::DEPARTMENT_BASE_PATH)
            ->setUploadDir(self::DEPARTMENT_UPLOAD_DIR)
            ->setUploadedFileNamePattern(self::DEPARTMENT_BASE_PATH.'[slug]-[timestamp].[extension]')
            ->hideOnIndex();


        yield TextField::new('address');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->showEntityActionsInlined()
            ->setDefaultSort([
                'dept_name' => 'ASC',
            ]);
    }

}
