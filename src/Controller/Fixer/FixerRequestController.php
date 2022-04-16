<?php

namespace App\Controller\Fixer;

use App\Entity\RequestActive;
use App\Repository\RequestActiveRepository;
use App\Repository\RequestRepository;
use App\Repository\ServiceRepository;
use App\Repository\ServiceStepRepository;
use App\Service\Constant;
use App\Service\ResolverService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FixerRequestController extends AbstractController
{
    #[Route('/open/request', name: 'open_requests', methods: ['GET'])]
    public function openRequestList(ResolverService $resolverService, RequestRepository $requestRepository): Response
    {
        $expertise = $resolverService->getFixerExpertise($this->getUser()->getId());
        $freeRequests = !empty($expertise) ? $requestRepository->findFreeRequests(array_keys($expertise['categories']), array_keys($expertise['dinos'])) : [];

        return $this->render('fixer/request/free_request_list.html.twig', [
            'requests' => $freeRequests
        ]);
    }

    #[Route('/open/request/{slug}', name: 'open_request', methods: ['GET', 'POST'])]
    public function openRequest(Request $request, string $slug, RequestRepository $requestRepository, ServiceRepository $serviceRepository, EntityManagerInterface $em, ServiceStepRepository $serviceStepRepository): Response
    {
        $requestEntity = $requestRepository->findRequestBySlug($slug);

        if ($request->isMethod('POST')) {
            $service = $serviceRepository->find($request->request->get('service'));
            $requestEntity->setService($service);

            $requestActive = (new RequestActive())
                ->setFixer($service->getFixer())
                ->setRequest($requestEntity)
                ->setStep($serviceStepRepository->findOneBy(['step' => 1]));

            $em->persist($requestEntity);
            $em->persist($requestActive);
            $em->flush();
            return $this->redirectToRoute('fixer_requests');
        }

        $availableServices = $serviceRepository->findFixerRequestRelatedServices($this->getUser()->getId(), $requestEntity->getCategory()?->getId(), $requestEntity->getDino()?->getId());
        return $this->render('fixer/request/free_request.html.twig', [
            'request' => $requestEntity,
            'available_services' => $availableServices
        ]);
    }

    #[Route('/request', name: 'requests', methods: ['GET', 'POST'])]
    public function requestList(RequestActiveRepository $activeRepository, Request $request, EntityManagerInterface $em, ServiceStepRepository $serviceStepRepository): Response
    {
        $activeRequests = $activeRepository->findUserRequestsByFixerId($this->getUser()->getId());
        return $this->render('fixer/request/request_list.html.twig', [
            'active_requests' => $activeRequests
        ]);
    }

    #[Route('/request/{id}', name: 'request', methods: ['GET', 'POST'])]
    public function request(int $id, Request $request, EntityManagerInterface $em, ServiceStepRepository $serviceStep, RequestActiveRepository $ra): Response
    {
        $activeReq = $ra->find($id);
        $currentStep = $activeReq->getStep();
        $nextStep = $serviceStep->findOneByStepValue($currentStep->getStep() + 1);

        if ($request->isMethod('POST') && $nextStep) {
            $activeReq->setStep($nextStep);
            $currentStep = $nextStep;
            $nextStep = $serviceStep->findOneByStepValue($currentStep->getStep() + 1);

            // This is final step
            if (!$nextStep) {
                $activeReq->setStatus(Constant::STATUS_DONE);
                $requestEntity = $activeReq->getRequest();
                $requestEntity->setStatus(Constant::STATUS_DONE);
                $em->persist($requestEntity);
            }

            $em->persist($activeReq);
            $em->flush();
        }

        return $this->render('fixer/request/request.html.twig', [
            'current_step' => $currentStep,
            'next_step' => $nextStep,
            'request' => $activeReq
        ]);
    }

    #[Route('/history', name: 'history', methods: ['GET'])]
    public function history(RequestActiveRepository $activeRepository): Response
    {
        $requests = $activeRepository->findFixerDoneRequests($this->getUser()->getId());
        return $this->render('fixer/request/history.html.twig', [
            'requests' => $requests
        ]);
    }
}
