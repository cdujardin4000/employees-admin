<?php

namespace App\Controller\Admin;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class EmployeeCrudController extends AbstractCrudController
{

    private EmployeeRepository $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {


        $this->employeeRepository = $employeeRepository;
    }


    public static function getEntityFqcn(): string
    {
        return Employee::class;
    }


    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->onlyOnIndex();

        yield Field::new('firstName')
            ->onlyOnForms();

        yield Field::new('lastName')
            ->onlyOnForms();

        yield TextField::new('fullName')
            ->hideOnForm();

        yield TextField::new('ActualDepartment')
            ->hideOnForm();

        yield DateField::new('birth_date');

        yield ChoiceField::new('gender')
            ->setChoices(fn () => [
                'F' => 'F',
                'M' => 'M',
                'X' => 'X'
            ]);

        yield ImageField::new('photo')
            ->setUploadDir('assets\img\employees')
            ->onlyOnForms();

        yield ImageField::new('avatar')
            ->setUploadDir('assets\img\employees\avatars')
            ->onlyOnForms();

        yield EmailField::new('email');

        yield DateField::new('hire_date');

        $roles = [
            'ROLE_USER',
            'ROLE_MANAGER',
            'ROLE_COMPTABLE',
            'ROLE_ADMIN',
            'ROLE_SUPER_ADMIN'
        ];
        yield ChoiceField::new('roles')
            ->setFormType(ChoiceType::class)
            ->setChoices(array_combine($roles, $roles))
            ->allowMultipleChoices()
            ->renderExpanded();

        yield BooleanField::new('isVerified');
    }
}
