<?php

namespace App\Controller\Customer;

use App\Entity\RequestActive;
use App\Form\RequestActiveType;
use App\Repository\RequestActiveRepository;
use App\Repository\RequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/request/active')]
class RequestActiveController extends AbstractController
{
    #[Route('/', name: 'request_active_index', methods: ['GET'])]
    public function index(RequestActiveRepository $requestActiveRepository): Response
    {
        $user_id = $this->getUser()->getId();
        $requests_actives = $requestActiveRepository->findUserRequestsByStatus($user_id, 2);
        return $this->render('customer/request_active/index.html.twig', [
            'request_actives' => $requests_actives,
        ]);
    }

    #[Route('/past', name: 'request_active_past', methods: ['GET'])]
    public function past(RequestActiveRepository $requestActiveRepository): Response
    {
        $user_id = $this->getUser()->getId();
        $requests_actives = $requestActiveRepository->findUserRequestsByStatus($user_id, 1);
        return $this->render('customer/request_active/past.html.twig', [
            'request_actives' => $requests_actives,
        ]);
    }

    #[Route('/new', name: 'request_active_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $requestActive = new RequestActive();
        $form = $this->createForm(RequestActiveType::class, $requestActive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($requestActive);
            $entityManager->flush();

            return $this->redirectToRoute('customer_request_active_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customer/request_active/new.html.twig', [
            'request_active' => $requestActive,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'request_active_show', methods: ['GET'])]
    public function show(RequestActive $requestActive): Response
    {
        return $this->render('customer/request_active/show.html.twig', [
            'request_active' => $requestActive,
        ]);
    }

    #[Route('/{id}/edit', name: 'request_active_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RequestActive $requestActive, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RequestActiveType::class, $requestActive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('customer_request_active_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customer/request_active/edit.html.twig', [
            'request_active' => $requestActive,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'request_active_delete', methods: ['POST'])]
    public function delete(Request $request, RequestActive $requestActive, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$requestActive->getId(), $request->request->get('_token'))) {
            $entityManager->remove($requestActive);
            $entityManager->flush();
        }

        return $this->redirectToRoute('customer_request_active_index', [], Response::HTTP_SEE_OTHER);
    }
}
