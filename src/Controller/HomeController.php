<?php

namespace App\Controller;

use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;
use App\Repository\TitleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @throws \Doctrine\DBAL\Exception
     */
    #[Route('/', name: 'app_home')]
    public function index(EmployeeRepository $employeeRepository, DepartmentRepository $departmentRepository, TitleRepository $titleRepository): Response
    {
       // dump($this->getUser());
        if ($this->getUser()?->getId() !== null)
        {
            if($this->getUser()->setCurrent($departmentRepository->find($employeeRepository->getCurrentDepartment($this->getUser()?->getId())))){
                $this->getUser()->setCurrentTitle($titleRepository->find($employeeRepository->getCurrentTitle($this->getUser()?->getId())));
            }
        }
        //dd($this->getUser());
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
