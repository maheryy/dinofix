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
use Symfony\Component\Routing\Annotation\Route;

#[Route('/request')]
class RequestController extends AbstractController
{
    #[Route('/', name: 'request_index', methods: ['GET'])]
    public function index(RequestRepository $requestRepository): Response
    {
        $user_id = $this->getUser()->getId();
        return $this->render('customer/request/index.html.twig', [
            'requests' => $requestRepository->findBy(['customer' => $user_id, 'status' => Constant::STATUS_DEFAULT, 'service' => null]),
        ]);
    }

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
            return $this->redirectToRoute('customer_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customer/request/new.html.twig', [
            'request' => $requestEntity,
            'form' => $form,
        ]);
    }

    #[Route('/active', name: 'request_active', methods: ['GET'])]
    public function activeRequestList(RequestActiveRepository $requestActiveRepository, ServiceStepRepository $serviceStepRepository): Response
    {
        $user_id = $this->getUser()->getId();
        $requests_actives = $requestActiveRepository->findUserRequestsByStatus($user_id, Constant::STATUS_DEFAULT);
        $steps = $serviceStepRepository->findAll();
        return $this->render('customer/request/active.html.twig', [
            'request_actives' => $requests_actives,
            'steps' => $steps
        ]);
    }

    #[Route('/history', name: 'request_history', methods: ['GET'])]
    public function past(RequestActiveRepository $requestActiveRepository): Response
    {
        $user_id = $this->getUser()->getId();
        $requests_actives = $requestActiveRepository->findUserRequestsByStatus($user_id, Constant::STATUS_DONE);
        return $this->render('customer/request/history.html.twig', [
            'request_actives' => $requests_actives,
        ]);
    }

    #[Route('/{id}', name: 'request_show', methods: ['GET'])]
    public function show(RequestEntity $requestEntity): Response
    {
        $user_id = $this->getUser()->getId();
        if ($user_id == $requestEntity->getCustomer()->getId()) {
            return $this->render('customer/request/show.html.twig', [
                'request' => $requestEntity,
            ]);
        }

        $this->redirectToRoute('customer_request_index');
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
        if ($this->isCsrfTokenValid('delete' . $requestEntity->getId(), $request->request->get('_token'))) {
            $entityManager->remove($requestEntity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('customer_request_index', [], Response::HTTP_SEE_OTHER);
    }
}
