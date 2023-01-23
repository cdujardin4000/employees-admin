<?php

namespace App\Controller\Admin;

use App\Entity\Partner;
use App\Entity\Title;
use App\Repository\DepartmentRepository;
use App\Repository\DeptEmpRepository;
use App\Repository\DeptManagerRepository;
use App\Repository\PartnerRepository;
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
use Doctrine\Persistence\ManagerRegistry;



class DashboardController extends AbstractDashboardController
{
    private ManagerRegistry $registry;
    private DeptManagerRepository $deptManagerRepository;
    private EmployeeRepository $employeeRepository;
    private ChartBuilderInterface $chartBuilderInterface;
    private DepartmentRepository $departmentRepository;
    private DeptEmpRepository $deptEmpRepository;


    public function __construct(
        ManagerRegistry $registry,
        Employee $employee,
        EmployeeRepository $employeeRepository,
        DepartmentRepository $departmentRepository,
        DeptManagerRepository $deptManagerRepository,
        ChartBuilderInterface $chartBuilderInterface,
        PartnerRepository $partnerRepository,
        DeptEmpRepository $deptEmpRepository
    )
    {
        $this->registry = $registry;
        $this->user = $employee;
        $this->chartbuilderInterface = $chartBuilderInterface;
        $this->employeeRepository = $employeeRepository;
        $this->deptManagerRepository = $deptManagerRepository;
        $this->departmentRepository = $departmentRepository;
        $this->partnerRepository = $partnerRepository;
        $this->deptEmpRepository = $deptEmpRepository;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws \Exception
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'app_admin')]
    public function index(DateTimeFormatter $timeFormater = null, ChartBuilderInterface $chartBuilderInterface = null): Response
    {
        assert(null !== $timeFormater);
        assert(null !== $chartBuilderInterface);

        $veterans = $this->employeeRepository->findVeterans();
        $arrivals = $this->employeeRepository->findArrivals();
        $current = $this->employeeRepository->getCurrentDepartment($this->getUser()?->getId());
        $partners = $this->partnerRepository->findAll();
        $departments = $this->departmentRepository->findAll();
        $departmentRepository = $this->departmentRepository;

        foreach ($departments as  $department)
        {
            $departments['manager'] =  $departmentRepository->getManager($departmentRepository->getManagerNo($department->getDeptNo()));
        }

        foreach ($veterans as $key => $veteran)
        {
            $veterans[$key]['ago'] =  $timeFormater->formatDiff(new DateTime($veteran['hire_date']) , new DateTime('now'));
        }

        /**foreach ($departments as $department)
        {
            $department->setCurrentManager($this->departmentRepository->getManager($this->departmentRepository->getManagerNo($department['dept_no'])));
        }**/

        $routeBuilder = $this->container->get(AdminUrlGenerator::class);

        $url = $routeBuilder->setController(EmployeeCrudController::class)->generateUrl();

        return $this->render('admin/index.html.twig', [
            'veterans' => $veterans,
            'arrivals' => $arrivals,
            'chart' => $this->createChart($chartBuilderInterface),
            'current' => $current,
            'partners' => $partners,
            'departments' => $departments,
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

        yield MenuItem::SubMenu(
            'Employees',
            'fas fa-users')->setSubItems([
                    MenuItem::linkToCrud(
                        'List',
                        'fa fa-list',
                        Employee::class
                    ),
                    MenuItem::linkToCrud(
                        'Add',
                        'fas fa-user-plus',
                        Employee::class
                    )->setAction(Crud::PAGE_NEW)
                        ->setPermission(
                        'ROLE_SUPER_ADMIN'
                    ),

                ]);

        yield MenuItem::SubMenu(
            'Departments',
            'fas fa-building'
            )->setSubItems([
                MenuItem::linkToCrud(
                    'List',
                    'fa fa-list',
                    Department::class
                ),
                MenuItem::linkToCrud(
                    'Add',
                    'fas fa-plus',
                    Department::class
                )->setAction(Crud::PAGE_NEW)
                    ->setPermission(
                        'ROLE_SUPER_ADMIN'
                    ),
            ]);


        yield MenuItem::SubMenu(
            'Titles',
            'fas fa-sitemap'
        )->setSubItems([
            MenuItem::linkToCrud(
                'List',
                'fa fa-list',
                Title::class
            ),
            MenuItem::linkToCrud(
                'Add',
                'fas fa-plus',
                Title::class
            )->setAction(Crud::PAGE_NEW)
                ->setPermission(
                    'ROLE_SUPER_ADMIN'
                ),
        ]);

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
                            'ROLE_SUPER_ADMIN'
                        ),
                        MenuItem::linkToCrud(
                            'Pending Approval',
                            'fa fa-warning',
                            Demand::class
                        )->setPermission(
                            'ROLE_MANAGER'
                        )->setController(
                            DemandCrudController::class
                        ),
        ]);

        yield MenuItem::SubMenu(
            'Partners',
            'fas fa-user-friends')->setSubItems([
            MenuItem::linkToCrud(
                'List',
                'fa fa-list',
                Partner::class
            ),
            MenuItem::linkToCrud(
                'Add',
                'fas fa-user-plus',
                Partner::class
            )->setAction(Crud::PAGE_NEW)
                ->setPermission(
                    'ROLE_SUPER_ADMIN'
                ),
        ]);

        yield MenuItem::section();

        yield MenuItem::linktoUrl(
            'Back to the website',
            'fas fa-home',
            $this->generateUrl('app_home')
        );

        yield MenuItem::section();

        yield MenuItem::SubMenu(
            'Know your friends',
            'fas fa-user-friends')->setSubItems([
            MenuItem::linkToUrl(
                'Frontend Masters',
                      '',
                'https://frontendmasters.com'
            ),
            MenuItem::linkToUrl(
                'SymfonyCasts',
                '',
                'https://symfonycasts.com/'
            ),
            MenuItem::linkToUrl(
                'Webpack',
                '',
                'https://webpack.js.org/'
            ),
            MenuItem::linkToUrl(
                'The valley of code',
                '',
                ' 	https://thevalleyofcode.com/'
            ),
            MenuItem::linkToUrl(
                'Cedric Dujardin',
                '',
                'https://portfolio.cedricdujardin.com/'
            ),
            MenuItem::linkToUrl(
                'Travesrsy Media',
                '',
                'https://www.traversymedia.com/'
            ),
        ]);

        yield MenuItem::section();

        yield MenuItem::linkToLogout('Logout', 'fas fa-sign-out-alt');
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


    /**
     * @throws Exception
     */
    public function configureUserMenu(UserInterface $user) : UserMenu
    {
        if (!$user instanceof Employee)
        {
            throw new RuntimeException('Wrong user');
        }

        return parent::configureUserMenu($user)
            ->setAvatarUrl($user?->getAvatar())
            ->addMenuItems([
               MenuItem::linkToUrl(
                   'My profile',
                   'fas-fa-user',
                   $this->generateUrl('app_employee_show',
                       [
                           'id' => $user->getId(),
                            'current' => $this->employeeRepository->getCurrentDepartment($user->getId())
                       ]
                   )
               ),
                MenuItem::linkToUrl(
                    'My department',
                    'fas-fa-building',
                    $this->generateUrl('app_department_show',
                        [
                            //'dept_no' => $this->deptEmpRepository->find($user->get_emp_no()),
                            'id' => $this->employeeRepository->getCurrentDepartment($user->getId())
                        ]
                    )
                )
            ]);
    }

    public function createChart(ChartBuilderInterface $chartBuilder): Chart
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
