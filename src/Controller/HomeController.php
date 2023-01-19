<?php

namespace App\Controller;

use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EmployeeRepository $employeeRepository, DepartmentRepository $departmentRepository,): Response
    {
       // dump($this->getUser());
        if ($this->getUser()?->getId() !== null)
        {
        $this->getUser()->setCurrent($departmentRepository->find($employeeRepository->getCurrentDepartment($this->getUser()?->getId())));
        }
        //dd($this->getUser());
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
