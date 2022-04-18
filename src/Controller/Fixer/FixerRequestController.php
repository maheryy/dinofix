<?php

namespace App\Controller\Fixer;

use App\Entity\RequestActive;
use App\Repository\RequestActiveRepository;
use App\Repository\RequestRepository;
use App\Repository\ServiceRepository;
use App\Repository\ServiceStepRepository;
use App\Service\RequestManager;
use App\Service\ResolverService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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

        if (!$requestEntity) {
            throw new BadRequestHttpException();
        }

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

    #[Route('/request', name: 'requests', methods: ['GET'])]
    public function requestList(RequestActiveRepository $activeRepository): Response
    {
        $activeRequests = $activeRepository->findUserRequestsByFixerId($this->getUser()->getId());
        return $this->render('fixer/request/request_list.html.twig', [
            'active_requests' => $activeRequests
        ]);
    }

    #[Route('/request/{slug}', name: 'request', methods: ['GET'])]
    public function request(string $slug, RequestManager $requestManager): Response
    {
        $activeRequest = $requestManager->getActiveRequest($slug);
        if (!$activeRequest) {
            throw new BadRequestHttpException();
        }

        $currentStep = $activeRequest->getStep();
        $nextStep = $requestManager->getActiveRequestNextStep($activeRequest);
        $countSteps = $requestManager->countActiveRequestSteps($activeRequest);


        return $this->render('fixer/request/request.html.twig', [
            'current_step' => $currentStep,
            'next_step' => $nextStep,
            'active_request' => $activeRequest,
            'count_steps' => $countSteps
        ]);
    }

    #[Route('/request/{slug}', name: 'request_action', methods: ['POST'])]
    public function requestAction(Request $request, string $slug, RequestManager $requestManager): Response
    {
        $activeRequest = $requestManager->getActiveRequest($slug);
        if (!$activeRequest) {
            throw new BadRequestHttpException();
        }
        $requestManager->handleRequestAction($activeRequest, $request->request->get('action'));

        return $this->redirectToRoute('fixer_request', ['slug' => $slug]);
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
