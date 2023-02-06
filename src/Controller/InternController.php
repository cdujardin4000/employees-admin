<?php

namespace App\Controller;

use App\Entity\Intern;
use App\Form\InternType;
use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;
use App\Repository\InternRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\DBAL\Exception;
use Proxies\__CG__\App\Entity\Employee;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/intern')]
class InternController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/manageIntern', name: 'app_intern_manageIntern', methods: ['GET', 'POST'])]
    public function manageIntern(Request $request, InternRepository $internRepository, EmployeeRepository $employeeRepository): Response
    {
        //dd($request);

        if ($request->getMethod() === 'POST') {
            $submittedToken = $request->request->get('token');
            $user = $employeeRepository->find($this->getUser());
            $submit = $request->request->get('submit');
            $supervisor = $request->request->get('supervisor');


            if($supervisor !== 'Select a value')
            {
                $supervisor = (int)$request->request->get('supervisor');
            }

            // values in submit btn
            $params = explode("-", $submit);
            $idIntern = (int)$params[0];
            $action = $params[1];

            $emp_no = (int)$request->request->get('emp_no');
            $validationToken = new csrfToken('intern-action', $submittedToken);
/*            $obj = new stdClass;
            $obj->supervisor = $supervisor;
            $obj->intern = $idIntern;
            $obj->action = $action;
            $obj->submit = $submit;
            $obj->emp_no = $emp_no;
            $obj->user = $user?->getId();
            $obj->validationToken = $validationToken->getValue();
            $obj->submittedToken = $submittedToken;
            //dd($obj);*/
            if ($user?->getId() !== $emp_no || $validationToken->getValue() !== $submittedToken)
            {
                $this->addFlash(
                    'warning',
                    'Forbidden User!'
                );

                return new Response(403);
            }
            $intern = $internRepository->find($idIntern);

            if ($action === 'terminate')
            {
                $intern?->setEmpNo(null);

                if($intern)
                {
                    $internRepository->save($intern, true);
                    $this->addFlash(
                        'success',
                        'Your changes were saved!'
                    );
                }
            }

            if($action === 'supervise')
            {
                if($supervisor === 'Select a value')
                {
                    $this->addFlash(
                        'error',
                        'Vous devez choisir un collaborateur à assigner au stagiaire sélectionné'
                    );

                } else if(($intern = $internRepository->find($intern)) && ($supervisor = $employeeRepository->find($supervisor))) {

                    $intern->setSupervisor($supervisor);
                    $internRepository->save($intern, true);

                    $this->addFlash(
                        'success',
                        'Le stagiaire à bien affecté au collaborateur'
                    );

                } else {

                    $this->addFlash(
                        'error',
                        'Il y a eu une erreur, veuillez réessayer plus tard'
                    );
                }
            }
        }

        if ($request->getMethod() === 'GET') {

            return $this->render('intern/manageIntern.html.twig', [
                'interns' => $internRepository->findBy([], ['dept' => 'ASC']),
                'employees' => $employeeRepository->findAll()
            ]);
        }

        return $this->redirectToRoute('app_intern_manageIntern', [
            'interns' => $internRepository->findBy([], ['dept' => 'ASC']),
            'employees' => $employeeRepository->findAll()
        ], Response::HTTP_SEE_OTHER);
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/superviseIntern', name: 'app_intern_superviseIntern', methods: ['GET', 'POST'])]
    public function superviseIntern(Request $request, InternRepository $internRepository, EmployeeRepository $employeeRepository): Response
    {
        $user = $employeeRepository->find($this->getUser());
        $user = $user?->getId();

        if ($request->getMethod() === 'POST')
        {
            $submittedToken = $request->request->get('token');
            //get usage data
            $id = $request->request->get('id');
            $emp_no = (int)$request->request->get('emp_no');
            $validationToken = new csrfToken($user . 'supervise-action' . $id, $submittedToken);
/*            $obj = new stdClass;
            $obj->emp_no = $emp_no;
            $obj->user = $user?->getId();
            $obj->validationToken = $validationToken->getValue();
            $obj->submittedToken = $submittedToken;
            ///dd($obj);*/
            if ($user !== $emp_no || $validationToken->getValue() !== $submittedToken) {
                $this->addFlash(
                    'warning',
                    'Forbidden User!'
                );
                return new Response(403);
            }
            if ($request->request->get('action') === 'supervise') {
                //process
                if ($intern = $internRepository->find($id))
                {

                    $intern->setSupervisor($employeeRepository->find($user));
                    $internRepository->save($intern, true);

                    $this->addFlash(
                        'success',
                        'Vous supervisez le stagiaire');
                }
            }else {
                $this->addFlash(
                    'error',
                    'Il y a eu une erreur veuillez réessayer plus tard');
            }
            $interns = $internRepository->findActualInterns($user);

            return $this->redirectToRoute('app_intern_superviseIntern', ['interns' => $interns], Response::HTTP_SEE_OTHER);
        }

        return $this->render('intern/superviseIntern.html.twig', [
            'interns' => $internRepository->findActualInterns($user)
        ]);
    }


    #[Route('/new', name: 'app_intern_new', methods: ['GET', 'POST'])]
    public function new(Request $request, InternRepository $internRepository): Response
    {
        $intern = new Intern();
        $form = $this->createForm(InternType::class, $intern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $internRepository->save($intern, true);

            return $this->redirectToRoute('app_intern_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('intern/new.html.twig', [
            'intern' => $intern,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_intern_show', methods: ['GET'])]
    public function show(Intern $intern): Response
    {
        return $this->render('intern/show.html.twig', [
            'intern' => $intern,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_intern_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Intern $intern, InternRepository $internRepository): Response
    {
        $form = $this->createForm(InternType::class, $intern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $internRepository->save($intern, true);

            return $this->redirectToRoute('app_intern_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('intern/edit.html.twig', [
            'intern' => $intern,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_intern_delete', methods: ['POST'])]
    public function delete(Request $request, Intern $intern, InternRepository $internRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$intern->getId(), $request->request->get('_token'))) {
            $internRepository->remove($intern, true);
        }

        return $this->redirectToRoute('app_intern_index', [], Response::HTTP_SEE_OTHER);
    }
}
