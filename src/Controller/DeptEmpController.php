<?php

namespace App\Controller;


use App\Repository\EmployeeRepository;
use App\Entity\DeptEmp;
use App\Form\DeptEmpType;
use App\Repository\DeptEmpRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/dept/emp')]
class DeptEmpController extends AbstractController
{
    #[Route('/', name: 'app_dept_emp_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $deptEmps = $entityManager
            ->getRepository(DeptEmp::class)
            ->findAll();

        return $this->render('dept_emp/index.html.twig', [
            'dept_emps' => $deptEmps,
        ]);
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     * @throws \Exception
     */
    #[Route('/new', name: 'app_dept_emp_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DeptEmpRepository $deptEmpRepository, EmployeeRepository $employeeRepository ): Response
    {
        $deptEmp = new DeptEmp();
        $form = $this->createForm(DeptEmpType::class, $deptEmp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $lastEmp = $employeeRepository->findLatest();

            $from = new DateTime('now');
            $to = new DateTime('9999-01-01');

           //dd($deptEmp);
            $deptEmpRepository->insertInto($lastEmp, $deptEmp->getDeptNo(), $from, $to);
            $deptEmpRepository->save($deptEmp, true);

            return $this->redirectToRoute('app_dept_emp_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dept_emp/new.html.twig', [
            'dept_emp' => $deptEmp,
            'form' => $form,
        ]);
    }

    #[Route('/{emp_no}', name: 'app_dept_emp_show', methods: ['GET'])]
    public function show(DeptEmp $deptEmp): Response
    {
        return $this->render('dept_emp/show.html.twig', [
            'dept_emp' => $deptEmp,
        ]);
    }

    #[Route('/{emp_no}/edit', name: 'app_dept_emp_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DeptEmp $deptEmp, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DeptEmpType::class, $deptEmp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dept_emp_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dept_emp/edit.html.twig', [
            'dept_emp' => $deptEmp,
            'form' => $form,
        ]);
    }

    #[Route('/{emp_no}', name: 'app_dept_emp_delete', methods: ['POST'])]
    public function delete(Request $request, DeptEmp $deptEmp, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$deptEmp->getEmpNo(), $request->request->get('_token'))) {
            $entityManager->remove($deptEmp);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dept_emp_index', [], Response::HTTP_SEE_OTHER);
    }
}
