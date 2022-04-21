<?php

namespace App\Controller\Customer;

use App\Entity\Request as RequestEntity;
use App\Form\RequestType;
use App\Repository\RequestActiveRepository;
use App\Repository\RequestRepository;
use App\Repository\ServiceRepository;
use App\Repository\ServiceStepRepository;
use App\Service\Constant;
use App\Service\Generator;
use App\Service\RequestManager;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/request')]
class RequestController extends AbstractController
{
    #[Route('/new', name: 'request_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RequestManager $requestManager): Response
    {
        $requestEntity = new RequestEntity();
        $form = $this->createForm(RequestType::class, $requestEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $requestEntity->setCustomer($this->getUser());
            $requestManager->createOpenRequest($requestEntity);

            $this->addFlash('success', 'Votre demande a été créée !');
            return $this->redirectToRoute('customer_open_requests', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customer/request/new.html.twig', [
            'request' => $requestEntity,
            'form' => $form,
        ]);
    }

    #[Route('/open', name: 'open_requests', methods: ['GET'])]
    public function index(RequestRepository $requestRepository): Response
    {
        $requests = $requestRepository->findCustomerOpenRequests($this->getUser());
        return $this->render('customer/request/index.html.twig', [
            'requests' => $requests,
        ]);
    }


    #[Route('/active', name: 'request_active', methods: ['GET'])]
    public function activeRequestList(RequestActiveRepository $requestActiveRepository, ServiceStepRepository $serviceStepRepository): Response
    {
        $requests_actives = $requestActiveRepository->findUserRequestsByStatus($this->getUser(), [Constant::STATUS_DEFAULT, Constant::STATUS_ACTIVE, Constant::STATUS_PAUSED]);
        $steps = $serviceStepRepository->findAll();
        return $this->render('customer/request/active.html.twig', [
            'request_actives' => $requests_actives,
            'steps' => $steps
        ]);
    }

    #[Route('/history', name: 'request_history', methods: ['GET'])]
    public function history(RequestActiveRepository $requestActiveRepository): Response
    {
        $requests_actives = $requestActiveRepository->findUserRequestsByStatus($this->getUser(), [Constant::STATUS_DONE, Constant::STATUS_CANCELLED]);
        return $this->render('customer/request/history.html.twig', [
            'request_actives' => $requests_actives,
        ]);
    }

    #[Route('/{slug}', name: 'request_show', methods: ['GET'])]
    public function show(string $slug, RequestRepository $requestRepository): Response
    {
        $requestEntity = $requestRepository->findRequestBySlug($slug);
        if (!$requestEntity) {
            throw new BadRequestHttpException();
        }

        if ($this->getUser()->getId() != $requestEntity->getCustomer()->getId()) {
            return $this->redirectToRoute('customer_open_requests');
        }

        return $this->render('customer/request/show.html.twig', [
            'request' => $requestEntity,
        ]);
    }

    #[Route('/{slug}/edit', name: 'request_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, string $slug, RequestRepository $requestRepository, EntityManagerInterface $entityManager): Response
    {
        $requestEntity = $requestRepository->findRequestBySlug($slug);
        if (!$requestEntity) {
            throw new BadRequestHttpException();
        }

        $form = $this->createForm(RequestType::class, $requestEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('customer_open_requests', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customer/request/edit.html.twig', [
            'request' => $requestEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'request_delete', methods: ['POST'])]
    public function delete(Request $request, RequestEntity $requestEntity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $requestEntity->getId(), $request->request->get('_token'))) {
            $entityManager->remove($requestEntity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('customer_open_requests', [], Response::HTTP_SEE_OTHER);
    }
}
