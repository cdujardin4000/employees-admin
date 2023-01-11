<?php

namespace App\Controller\Admin;

use App\Repository\DepartmentRepository;
use App\Repository\DeptManagerRepository;
use DateTime;
use App\Entity\Demand;
use App\Entity\Department;
use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\DBAL\Exception;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractDashboardController
{
    private DeptManagerRepository $deptManagerRepository;
    private EmployeeRepository $employeeRepository;
    private ChartBuilderInterface $chartBuilderInterface;
    private DepartmentRepository $departmentRepository;

    public function __construct(Employee $employee, EmployeeRepository $employeeRepository, DepartmentRepository $departmentRepository, DeptManagerRepository $deptManagerRepository, ChartBuilderInterface $chartBuilderInterface)
    {
        $this->user = $employee;
        $this->chartbuilder = $chartBuilderInterface;
        $this->employeeRepository = $employeeRepository;
        $this->deptManagerRepository = $deptManagerRepository;
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws \Exception
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'app_admin')]
    public function index(DateTimeFormatter $timeFormater = null, ChartBuilderInterface $chartBuilder = null): Response
    {

        assert(null !== $timeFormater);
        assert(null !== $chartBuilder);

        $veterans = $this->employeeRepository->findVeterans();
        $arrivals = $this->employeeRepository->findArrivals();



        foreach ($veterans as $veteran)
        {
            $veteran['ago'] = $timeFormater->formatDiff(new DateTime($veteran['hire_date']), new DateTime('now'));
            //dd($veteran['ago']);
        }

        $routeBuilder = $this->container->get(AdminUrlGenerator::class);

        $url = $routeBuilder->setController(EmployeeCrudController::class)->generateUrl();

        return $this->render('admin/index.html.twig', [
            'veterans' => $veterans,
            'arrivals' => $arrivals,
            'chart' => $this->createChart($chartBuilder),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Welcome to Encore CORPS');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard(
            'Dashboard',
            'fas fa-users-cog'
        );

        yield MenuItem::section('Content');

        yield MenuItem::linkToCrud(
            'Employees',
            'fas fa-users',
            Employee::class
        );

        yield MenuItem::linkToCrud(
            'Departments',
            'fas fa-building',
            Department::class
        );

        yield MenuItem::subMenu(
            'Demands',
            'fa fa-question-circle'
                )->setSubItems([
                        MenuItem::linkToCrud(
                            'All',
                            'fa fa-list',
                            Demand::class
                        )->setController(
                            DemandCrudController::class
                        )->setPermission(
                            'ROLE_ADMIN'
                        ),
                        MenuItem::linkToCrud(
                            'Pending Approval',
                            'fa fa-warning',
                            Demand::class
                        )->setPermission(
                            'ROLE_MANAGER'
                        )->setController(
                            DemandsPendingCrudController::class
                        ),
        ]);

        yield MenuItem::section();


        yield MenuItem::linktoUrl(
            'Back to the website',
            'fas fa-home',
            $this->generateUrl('app_home')
        );
    }

    public function configureCrud(): Crud
    {
        return parent::configureCrud()
            ->setDefaultSort([
                'id' => 'DESC',
            ])
            ->overrideTemplate('crud/field/id', 'admin/field/id_with_icon.html.twig');
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()

            ->addWebpackEncoreEntry('admin');

    }


    public function configureUserMenu(UserInterface $user) : UserMenu
    {
        if (!$user instanceof Employee)
        {
            throw new RuntimeException('Wrong user');
        }

        return parent::configureUserMenu($user)
            ->setAvatarUrl($user->getAvatar())
            ->addMenuItems([
               MenuItem::linkToUrl(
                   'My profile',
                   'fas-fa-user',
                   $this->generateUrl('app_employee_show',
                       [
                           'id' => $user->getId(),
                           //'currentDepartment' => $this->employeeRepository->getCurrentDepartment($user->getId()),
                       ]
                   )
               )
            ]
        );
    }

    private function createChart(ChartBuilderInterface $chartBuilder): Chart
    {
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        return $chart;
    }
}
