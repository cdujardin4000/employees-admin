<?php

namespace App\Controller\Admin;

use App\Entity\Department;
use App\Entity\Employee;
use App\Form\DeptEmpType;
use App\Repository\EmployeeRepository;
use DateTime;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class EmployeeCrudController extends AbstractCrudController
{
    public const EMPLOYEE_BASE_PATH = 'assets/img/employees/';
    public const EMPLOYEE_UPLOAD_DIR = 'public/uploads/employees/';
    public const EMPLOYEE_PATH = 'uploads/employees/';
    public const EMP_TO_DEPT_ACTION = 'Save and assign department';


    public function __construct(
        public UserPasswordHasherInterface $userPasswordHasher
    ) {}

    public static function getEntityFqcn(): string
    {
        return Employee::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $assign = Action::new(self::EMP_TO_DEPT_ACTION)
            ->linkToRoute('app_dept_emp_new')
            ->setCssClass('btn btn-info');

        return $actions
            ->add(Crud::PAGE_NEW, $assign)
            ->reorder(Crud::PAGE_NEW, [self::EMP_TO_DEPT_ACTION, Action::SAVE_AND_RETURN]);
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }

    private function hashPassword() {
        return function($event) {
            $form = $event->getForm();
            if (!$form->isValid()) {
                return;
            }
            $password = $form->get('password')->getData();
            if ($password === null) {
                return;
            }

/*            $hash = $this->userPasswordHasher->hashPassword($entityInstance, $password);
            $form->getData()->setPassword($hash);*/
        };
    }



    public function persistEntity(EntityManagerInterface  $entityManager, $entityInstance): void
    {

        if(!$entityInstance instanceof Employee) {
            return;
        }

        $entityInstance->setCreatedAt(new DateTime);
        // encode the plain password
        /**$entityInstance->setPassword(
                $entityManager->hashPassword(
                    $entityInstance,
                    $entityInstance->getPassword()->getData()
                )
            );**/

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        if ($this->isGranted('ROLE_SUPER_ADMIN'))
        {
/*            $aff = $this->getUser()?->getAffectations();
            //$first = $aff->orderBy(['to_date' => Criteria::DESC])->first();

            //$dept = $this->getUser()?->getCurrentAffectation();
            $today = new DateTime('9999-01-01');
            //dd($unlimited);
            $expr = new Comparison('to_date', Comparison::GT, $today);

            $criteria = new Criteria();

            $criteria->where($expr);

            $dept = $aff->matching($criteria);
            dd($dept);*/
            return $queryBuilder;
        }
        if ($this->isGranted('ROLE_MANAGER')) {
/*            $dept = $this->getUser()?->getAffectations();
            dd($dept);*/
            return $queryBuilder;
        }
        //dd($this->getDepartment);
        $queryBuilder->andWhere('entity.id = :id')->setParameter('id', $this->getUser()?->getId());
        return $queryBuilder;
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

       // yield TextField::new('current')
           // ->hideOnForm();

        yield DateField::new('birth_date');

        yield ChoiceField::new('gender')
            ->setChoices(fn () => [
                'F' => 'F',
                'M' => 'M',
                'X' => 'X'
            ]);

        yield ImageField::new('photo')
            ->formatValue(static function ($value, ?Employee $user) {
                return $user?->getPhoto();
            })
            ->setBasePath(self::EMPLOYEE_BASE_PATH)
            ->setUploadDir(self::EMPLOYEE_UPLOAD_DIR.'photos')
            ->setUploadedFileNamePattern(self::EMPLOYEE_PATH.'photos/[slug]-[timestamp].[extension]');


       yield AvatarField::new('avatar')
            ->formatValue(static function ($value, Employee $employee) {
                return $employee?->getAvatar();
            })
            ->hideOnForm();

        yield ImageField::new('avatarUrl')
            ->formatValue(static function ($value, ?Employee $user) {
                return $user?->getAvatarUrl();
            })
            ->setBasePath(self::EMPLOYEE_BASE_PATH)
            ->setUploadDir(self::EMPLOYEE_UPLOAD_DIR.'avatars')
            ->setUploadedFileNamePattern(self::EMPLOYEE_PATH.'avatars/[slug]-[timestamp].[extension]')
            ->onlyOnForms();

        yield EmailField::new('email');

        yield TextField::new('password')
            ->onlyOnForms();

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

        yield BooleanField::new('isVerified');

    }


    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
           // ->setEntityPermission('ADMIN_USER_EDIT')
          //  ->setEntityPermission('ADMIN_USER_SHOW')
            ->showEntityActionsInlined()
            ->setDefaultSort([
                'id' => 'ASC',
            ]);
    }
}
