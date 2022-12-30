<?php

namespace App\Controller;

use App\Entity\Demand;
use App\Form\DemandType;
use App\Repository\DemandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/demand')]
class DemandController extends AbstractController
{
    #[Route('/', name: 'app_demand_index', methods: ['GET'])]
    public function index(DemandRepository $demandRepository): Response
    {
        return $this->render('demand/index.html.twig', [
            'demands' => $demandRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_demand_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DemandRepository $demandRepository): Response
    {
        $demand = new Demand();
        $form = $this->createForm(DemandType::class, $demand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demandRepository->save($demand, true);

            return $this->redirectToRoute('app_demand_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demand/new.html.twig', [
            'demand' => $demand,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demand_show', methods: ['GET'])]
    public function show(Demand $demand): Response
    {
        return $this->render('demand/show.html.twig', [
            'demand' => $demand,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_demand_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Demand $demand, DemandRepository $demandRepository): Response
    {
        $form = $this->createForm(DemandType::class, $demand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demandRepository->save($demand, true);

            return $this->redirectToRoute('app_demand_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demand/edit.html.twig', [
            'demand' => $demand,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demand_delete', methods: ['POST'])]
    public function delete(Request $request, Demand $demand, DemandRepository $demandRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demand->getId(), $request->request->get('_token'))) {
            $demandRepository->remove($demand, true);
        }

        return $this->redirectToRoute('app_demand_index', [], Response::HTTP_SEE_OTHER);
    }
}
