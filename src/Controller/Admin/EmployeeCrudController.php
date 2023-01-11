<?php

namespace App\Controller\Admin;

use App\Entity\Employee;
use App\Entity\User;
use App\Repository\EmployeeRepository;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
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

    public function __construct(ManagerRegistry $registry, EmployeeRepository $employeeRepository)
    {
        $this->registry = $registry;
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

        yield TextField::new('firstName')
            ->onlyOnForms()
            ->setMaxLength(14);

        yield TextField::new('lastName')
            ->onlyOnForms()
            ->setMaxLength(16);

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
            ->setUploadDir('assets/img/employees')
            ->onlyOnForms();

        /**yield AvatarField::new('avatar')
            ->formatValue(static function ($value, Employee $employee) {
                return $employee?->getAvatar();
            })
            ->hideOnForm();**/

        yield ImageField::new('avatar')
            ->setBasePath('assets/img/employees/avatars')
            ->setUploadDir('public/uploads/')
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
            ->onlyOnForms();

        yield EmailField::new('email');

        yield DateField::new('hire_date');

        $roles = [
            'ROLE_SUPER_ADMIN',
            'ROLE_ADMIN',
            'ROLE_COMPTABLE',
            'ROLE_MANAGER',
            'ROLE_USER',
        ];
        yield ChoiceField::new('roles')
            ->setFormType(ChoiceType::class)
            ->setChoices(array_combine($roles, $roles))
            ->allowMultipleChoices()
            ->renderExpanded()
            ->renderAsBadges();

        yield BooleanField::new('isVerified')
            ->hideOnForm();
    }
}
