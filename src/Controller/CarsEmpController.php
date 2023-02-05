<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\CarsEmp;
use App\Entity\Employee;
use App\Form\CarsEmpType;
use App\Repository\CarRepository;
use App\Repository\CarsEmpRepository;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;

#[Route('/cars/emp')]
class CarsEmpController extends AbstractController
{
    #[Route('/switchCars', name: 'app_cars_emp_switchCars', methods: ['GET', 'POST'])]
    public function switchCars(EntityManagerInterface $entityManager, Request $request, CarsEmpRepository $carsEmpRepository, EmployeeRepository $employeeRepository): Response
    {
        $cars = $entityManager->getRepository(Car::class);
        $employees = $entityManager->getRepository(Employee::class);
        //dd($cars);
        if ($request->getMethod() === 'GET') {

            return $this->render('cars_emp/switchCars.html.twig', [
                'cars_emps' => $carsEmpRepository->findAll(),
                'cars' => $cars,
                'employees' => $employees
            ]);
        }

        if ($request->getMethod() === 'POST')
        {
            $today = new \DateTime('now');
            $req = $request->request->all();

            if (count($req['submit']) !== 2)
            {
                $this->addFlash(
                    'error',
                    'Vous devez choisir 2 et uniquement 2 éléments'
                );
            } else {

                $submittedToken = $req['token'];
                $user = $employeeRepository->find($this->getUser());
                $emp_no = (int)$req['emp_no'];
                $validationToken = new csrfToken('switchCars-action', $submittedToken);
/*                $obj = new stdClass;

                $obj->emp_no = $emp_no;
                $obj->user = $user?->getId();
                $obj->validationToken = $validationToken->getValue();
                $obj->submittedToken = $submittedToken;
                //dd($obj);*/
                if ($user?->getId() === $emp_no && $submittedToken === $validationToken->getValue())
                {
                    [$carsEmp1, $carsEmp2] = $req['submit'];

                    $params1 = explode("-", $carsEmp1);
                    $car1 = (int)$params1[0];
                    $emp1 = (int)$params1[1];

                    $params2 = explode("-", $carsEmp2);
                    $car2 = (int)$params2[0];
                    $emp2 = (int)$params2[1];

                    $entity1 = $carsEmpRepository->findOneBy(['car_id' => $car1]);
                    $entity2 = $carsEmpRepository->findOneBy(['car_id' => $car2]);

                    if($entity1 && $entity2)
                    {
                        $entity1->setEmpNo($emp2);
                        $entity1->setFromDate($today);
                        //dd($entity1);
                        $entity2->setEmpNo($emp1);
                        $entity2->setFromDate($today);

                        $carsEmpRepository->save($entity1, true );
                        $carsEmpRepository->save($entity2, true );

                        $this->addFlash(
                            'success',
                            'Le changement à bien été effectué'
                        );
                    }
                }
            }
        }

        return $this->redirectToRoute('app_cars_emp_switchCars', [
            'cars_emps' => $carsEmpRepository->findAll()
        ], Response::HTTP_SEE_OTHER);
    }

    #[Route('/new', name: 'app_cars_emp_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CarsEmpRepository $carsEmpRepository): Response
    {
        $carsEmp = new CarsEmp();
        $form = $this->createForm(CarsEmpType::class, $carsEmp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carsEmpRepository->save($carsEmp, true);

            return $this->redirectToRoute('app_cars_emp_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cars_emp/new.html.twig', [
            'cars_emp' => $carsEmp,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cars_emp_show', methods: ['GET'])]
    public function show(CarsEmp $carsEmp): Response
    {
        return $this->render('cars_emp/show.html.twig', [
            'cars_emp' => $carsEmp,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cars_emp_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CarsEmp $carsEmp, CarsEmpRepository $carsEmpRepository): Response
    {
        $form = $this->createForm(CarsEmpType::class, $carsEmp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carsEmpRepository->save($carsEmp, true);

            return $this->redirectToRoute('app_cars_emp_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cars_emp/edit.html.twig', [
            'cars_emp' => $carsEmp,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cars_emp_delete', methods: ['POST'])]
    public function delete(Request $request, CarsEmp $carsEmp, CarsEmpRepository $carsEmpRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$carsEmp->getId(), $request->request->get('_token'))) {
            $carsEmpRepository->remove($carsEmp, true);
        }

        return $this->redirectToRoute('app_cars_emp_index', [], Response::HTTP_SEE_OTHER);
    }
}
