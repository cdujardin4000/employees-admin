<?php

namespace App\Controller;

use App\Entity\Leave;
use App\Form\LeaveType;
use App\Repository\LeaveRepository;
use DateTime;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\Expr\Comparison;
use HttpResponseException;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route('/leave')]
class LeaveController extends AbstractController
{
    #[Route('/', name: 'app_leave_index', methods: ['GET'])]
    public function index(LeaveRepository $leaveRepository): Response
    {
        return $this->render('leave/index.html.twig', [
            'leaves' => $leaveRepository->findActualLeaves()
        ]);
    }

    #[Route('/new', name: 'app_leave_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LeaveRepository $leaveRepository): Response
    {
        $leave = new Leave();
        $form = $this->createForm(LeaveType::class, $leave);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $leaveRepository->save($leave, true);

            return $this->redirectToRoute('app_leave_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('leave/new.html.twig', [
            'leave' => $leave,
            'form' => $form,
        ]);
    }


    #[Route('/{leave_id}', name: 'app_leave_show', methods: ['GET'])]
    public function show(Leave $leave): Response
    {
        return $this->render('leave/show.html.twig', [
            'leave' => $leave,
        ]);
    }

    #[Route('/{leave_id}/edit', name: 'app_leave_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Leave $leave, LeaveRepository $leaveRepository): Response
    {
        $form = $this->createForm(LeaveType::class, $leave);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $leaveRepository->save($leave, true);

            return $this->redirectToRoute('app_leave_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('leave/edit.html.twig', [
            'leave' => $leave,
            'form' => $form,
        ]);
    }

    #[Route('/{leave_id}', name: 'app_leave_delete', methods: ['POST'])]
    public function delete(Request $request, Leave $leave, LeaveRepository $leaveRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$leave->getleaveId(), $request->request->get('_token'))) {
            $leaveRepository->remove($leave, true);
        }

        return $this->redirectToRoute('app_leave_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{leave_id}/return', name: 'app_leave_return', methods: ['POST'])]
    public function action(Request $request, LeaveRepository $leaveRepository): Response|HttpResponseException
    {
        // get common data
        $action = $request->request->get('action');
        $submittedToken = $request->request->get('token');
        $user = $this->getUser()?->getId();

        //get usage data
        $leave_id = $request->request->get('leave_id');

        //generate the validationToken
        $validationToken = new csrfToken($user . 'leave-action' . $leave_id, $submittedToken);

        //check data
/*        $obj = new stdClass;
        $obj->emp_no = intVal($emp_no);
        $obj->user = $user;
        $obj->validationToken = $validationToken->getValue();
        $obj->submittedToken = $submittedToken;*/
        //dd($obj);
        //control data
        if ($validationToken->getValue() !== $submittedToken) {
            return new HttpResponseException();
        }

        //process

        $leave = $leaveRepository->find($leave_id);

        if ($leave && $action === 'return') {
            $leave->setToDate(new DateTime('now') );
        }


        $leaveRepository->save($leave, true);

        return $this->redirectToRoute('app_leave_index', ['leaves' => $leaveRepository->findActualLeaves() ], Response::HTTP_SEE_OTHER);
    }

}
