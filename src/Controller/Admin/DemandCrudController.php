<?php

namespace App\Controller\Admin;

use App\Entity\Demand;
use App\Entity\Employee;
use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('ROLE_SUPER_ADMIN')]
class DemandCrudController extends AbstractCrudController
{
    private EmployeeRepository $employeeRepository;

    public function __construct(
        EmployeeRepository $employeeRepository,
        DepartmentRepository $departmentRepository,
    ){
        $this->employeeRepository = $employeeRepository;
        $this->departmentRepository = $departmentRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Demand::class;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {//dump($this);
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            return $queryBuilder;
        }

        if ($this->isGranted('ROLE_COMPTABLE')) {
            $currentDep = $this->employeeRepository->getCurrentDepartment($this->getUser()?->getId());
            //$affectationDemands = $this->departmentRepository->getAffectationDemands($currentDep);

            //$queryBuilder->select(Demand::class)->from('demands' , 'd')->where('d.about = :cd')->setParameter('cd', $currentDep);
            //$queryBuilder->select('d.id', 'd.about', )->from('demands', 'd')->leftJoin('dept_emp.emp_no','de')on('de.emp_no='.$this->getUser()->getCurrent())->andWhere('de.deptNo='.$this->getUser()?->getCurrent());
        }

        if ($this->isGranted('ROLE_MANAGER')) {
            //$currentDep = $this->employeeRepository->getCurrentDepartment($this->getUser()?->getId());
            //$affectationDemands = $this->departmentRepository->getAffectationDemands($currentDep);

            //$queryBuilder->select(Demand::class)->from('demands' , 'd')->where('d.about = :cd')->setParameter('cd', $currentDep);
            //$queryBuilder->select('d.id', 'd.about', )->from('demands', 'd')->leftJoin('dept_emp.emp_no','de')on('de.emp_no='.$this->getUser()->getCurrent())->andWhere('de.deptNo='.$this->getUser()?->getCurrent());
        }
            //$queryBuilder->andWhere('entity.id = :id')->setParameter('id', $this->getUser()?->getId());
        //}

        $queryBuilder->andWhere('entity.id = :id')->setParameter('id', $this->getUser()?->getId());
        return $queryBuilder;
    }


    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->setPermission(Action::INDEX, 'ROLE_MANAGER')
            ->setPermission(Action::DETAIL, 'ROLE_MANAGER')
            ->setPermission(Action::EDIT, 'ROLE_MANAGER')
            ->setPermission(Action::NEW, 'ROLE_SUPER_ADMIN')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_SUPER_ADMIN');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->onlyOnIndex();

        yield ChoiceField::new('type')
            ->setChoices(fn () => [
                'reaffectation' => 'reaffectation',
                'augmentation' => 'augmentation',
            ]);

        yield TextField::new('about')
            ->setMaxLength(15);

        yield BooleanField::new('reviewed')
            ->hideWhenCreating()
            ->renderAsSwitch(false);

        yield BooleanField::new('status')
            ->hideWhenCreating()
            ->renderAsSwitch(false);

        yield AssociationField::new('employee')
            ->autocomplete()
            /**->setQueryBuilder(function (QueryBuilder $qb) {
                $qb->andWhere('entity.enabled = :true')
                    ->setParameter('enabled', true);
            });**/
            ->formatValue(static function ($value, ?Demand $demand): ?string {
                if (!$user = $demand?->getEmployee()) {
                    return null;
                }
                return sprintf('%s&nbsp;(%s)', $user->getEmail(), $user->getDemands()->count());
            });
    }



    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->showEntityActionsInlined()
            ->setDefaultSort([
                'employee' => 'DESC',
                'reviewed' => 'ASC'
            ]);
    }
}
