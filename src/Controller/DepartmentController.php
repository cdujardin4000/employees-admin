<?php

namespace App\Controller;

use App\Entity\Department;
use App\Form\DepartmentType;
use App\Repository\DepartmentRepository;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/department')]
class DepartmentController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/', name: 'app_department_index', methods: ['GET'])]
    public function index(DepartmentRepository $departmentRepository): Response
    {


        return $this->render('department/index.html.twig', [
            'departments' => $departmentRepository->findAll(),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/new', name: 'app_department_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DepartmentRepository $departmentRepository): Response
    {
        $department = new Department();
        $newDeptNo = $departmentRepository->getLastDepartmentRemoveDAndAddOneBeforeAddingD();
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $department->setDeptNo($newDeptNo);
            $departmentRepository->save($department, true);

            return $this->redirectToRoute('app_department_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('department/new.html.twig', [
            'department' => $department,
            'form' => $form,

        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/{id}', name: 'app_department_show', methods: ['GET'])]
    public function show(Department $department, DepartmentRepository $departmentRepository): Response
    {
        return $this->render('department/show.html.twig', [
            'department' => $department,
            'nbEmployees' => $departmentRepository->getNbEmployees($department->getDeptNo()),
            'manager' => $departmentRepository->getManager($departmentRepository->getManagerNo($department->getDeptNo())),
            'offers' => $department->getOffers(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_department_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Department $department, DepartmentRepository $departmentRepository): Response
    {
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $departmentRepository->save($department, true);

            return $this->redirectToRoute('app_department_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('department/edit.html.twig', [
            'department' => $department,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_department_delete', methods: ['POST'])]
    public function delete(Request $request, Department $department, DepartmentRepository $departmentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$department->getId(), $request->request->get('_token'))) {
            $departmentRepository->remove($department, true);
        }

        return $this->redirectToRoute('app_department_index', [], Response::HTTP_SEE_OTHER);
    }
}
