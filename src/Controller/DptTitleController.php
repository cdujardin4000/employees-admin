<?php

namespace App\Controller;

use App\Entity\DptTitle;
use App\Form\DptTitleType;
use App\Repository\DptTitleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dpt/title')]
class DptTitleController extends AbstractController
{
    #[Route('/', name: 'app_dpt_title_index', methods: ['GET'])]
    public function index(DptTitleRepository $dptTitleRepository): Response
    {
        return $this->render('dpt_title/index.html.twig', [
            'dpt_titles' => $dptTitleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_dpt_title_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DptTitleRepository $dptTitleRepository): Response
    {
        $dptTitle = new DptTitle();
        $form = $this->createForm(DptTitleType::class, $dptTitle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dptTitleRepository->save($dptTitle, true);

            return $this->redirectToRoute('app_dpt_title_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dpt_title/new.html.twig', [
            'dpt_title' => $dptTitle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dpt_title_show', methods: ['GET'])]
    public function show(DptTitle $dptTitle): Response
    {
        return $this->render('dpt_title/show.html.twig', [
            'dpt_title' => $dptTitle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dpt_title_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DptTitle $dptTitle, DptTitleRepository $dptTitleRepository): Response
    {
        $form = $this->createForm(DptTitleType::class, $dptTitle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dptTitleRepository->save($dptTitle, true);

            return $this->redirectToRoute('app_dpt_title_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dpt_title/edit.html.twig', [
            'dpt_title' => $dptTitle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dpt_title_delete', methods: ['POST'])]
    public function delete(Request $request, DptTitle $dptTitle, DptTitleRepository $dptTitleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dptTitle->getId(), $request->request->get('_token'))) {
            $dptTitleRepository->remove($dptTitle, true);
        }

        return $this->redirectToRoute('app_dpt_title_index', [], Response::HTTP_SEE_OTHER);
    }
}
