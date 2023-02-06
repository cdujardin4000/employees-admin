<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\EmployeeRepository;
use App\Repository\ProjectRepository;
use HttpResponseException;
use JetBrains\PhpStorm\NoReturn;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;

#[Route('/project')]
class ProjectController extends AbstractController
{
    #[Route('/', name: 'app_project_index', methods: ['GET', 'POST'])]
    public function index(Request $request, ProjectRepository $projectRepository, EmployeeRepository $employeeRepository): Response
    {
        if ($request->getMethod() === 'POST') {
            //define function
            function removeFromProject($projectEntity, $projectRepository) {
                $projectRepository->save($projectEntity, true);
            }
            // get common data
            $action = $request->request->get('action');
            $submittedToken = $request->request->get('token');
            $user = $this->getUser()?->getId();

            //get usage data
            $project = $request->request->get('project');
            $project_id = $request->request->get('project_id');
            $employee = $request->request->get('employee');

            //generate the validationToken
            $validationToken = new csrfToken($user . 'project-action' . $project_id, $submittedToken);

            /**
            //check data
            $obj = new stdClass;
            $obj->employee = $employee;
            $obj->user = $user;
            $obj->validationToken = $validationToken->getValue();
            $obj->submittedToken = $submittedToken;
            //dd($obj);*/

            //check if  tokens are  the same both side of the request
            if (!$user || $validationToken->getValue() !== $submittedToken) {
                return $this->redirectToRoute('app_logout', [], Response::HTTP_SEE_OTHER);
            }

            $projectEntity = $projectRepository->find($project_id);
            $employeeEntity = $employeeRepository->find($employee);

            if ($projectEntity && $employeeEntity && $action === 'remove' && $projectEntity->getDescription() === $project && $projectEntity->removeEmployee($employeeEntity)) {

                removeFromProject($projectEntity, $projectRepository);
            }
        }

        return $this->render('project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_project_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProjectRepository $projectRepository): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectRepository->save($project, true);

            return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('project/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{project_id}', name: 'app_project_show', methods: ['GET'])]
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/{project_id}/edit', name: 'app_project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project, ProjectRepository $projectRepository): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectRepository->save($project, true);

            return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{project_id}', name: 'app_project_delete', methods: ['POST'])]
    public function delete(Request $request, Project $project, ProjectRepository $projectRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getProjectId(), $request->request->get('_token'))) {
            $projectRepository->remove($project, true);
        }

        return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
    }
}
