<?php

namespace App\Controller\Customer;

use App\Entity\Request as RequestEntity;
use App\Form\RequestType;
use App\Repository\RequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/request')]
class RequestController extends AbstractController
{
    public function __toString()
    {
        return $this->requestEntity;
    }

    #[Route('/', name: 'request_index', methods: ['GET'])]
    public function index(RequestRepository $requestRepository): Response
    {
        $user_id = $this->getUser()->getId();
        return $this->render('customer/request/index.html.twig', [
            'requests' => $requestRepository->findBy(['customer' => $user_id]),
        ]);
    }

    #[Route('/new', name: 'request_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $requestEntity = new RequestEntity();
        $form = $this->createForm(RequestType::class, $requestEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $requestEntity->setCustomer($user)->setReference(123456)->setStatus(0);
            $entityManager->persist($requestEntity);
            $entityManager->flush();

            return $this->redirectToRoute('customer_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customer/request/new.html.twig', [
            'request' => $requestEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'request_show', methods: ['GET'])]
    public function show(RequestEntity $requestEntity): Response
    {
        $user_id = $this->getUser()->getId();
        if($user_id == $requestEntity->getCustomer()->getId()) {
            return $this->render('customer/request/show.html.twig', [
                'request' => $requestEntity,
            ]);
        } else {
            $this->redirectToRoute('customer_request_index');
        }
    }

    #[Route('/{id}/edit', name: 'request_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RequestEntity $requestEntity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RequestType::class, $requestEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('customer_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customer/request/edit.html.twig', [
            'request' => $requestEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'request_delete', methods: ['POST'])]
    public function delete(Request $request, RequestEntity $requestEntity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$requestEntity->getId(), $request->request->get('_token'))) {
            $entityManager->remove($requestEntity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('customer_request_index', [], Response::HTTP_SEE_OTHER);
    }
}
