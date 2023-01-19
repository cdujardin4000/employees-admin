<?php

namespace App\Controller\Admin;

use App\Entity\Demand;
use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('ROLE_MANAGER')]
class DemandManagerCrudController extends AbstractCrudController
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
}