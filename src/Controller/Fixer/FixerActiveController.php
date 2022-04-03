<?php

namespace App\Controller\Fixer;

use App\Entity\ServiceStep;
use App\Repository\RequestActiveRepository;
use App\Repository\RequestRepository;
use App\Repository\ServiceRepository;
use App\Repository\ServiceStepRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FixerActiveController extends AbstractController
{
    #[Route('/active', name: 'active', methods: ['GET', 'POST'])]
    public function getHome(RequestActiveRepository $activeRepository, RequestRepository $requestRepository, ServiceRepository $serviceRepository, Request $request, EntityManagerInterface $em, ServiceStepRepository $serviceStep): Response
    {
        if ($request->getMethod() == 'POST') {
            $id = $request->request->get('id');
            $acceptedReq = $activeRepository->find($id);
            $acceptedReq->setStep($serviceStep->find(2));
            $em->persist($acceptedReq);
            $em->flush();
        }

        $fixerServices = $serviceRepository->findFixerServicesById(1, -1);
        $matchingRequests = [];
        foreach ($fixerServices as $fixerService) {
            $result = $requestRepository->findByCategoryAndDino($fixerService->getDino()->getId(), $fixerService->getCategory()->getId());
            if (sizeof($result) > 0 && $result[0]->getFixer()->getId() == null) {
                array_push($matchingRequests, $result);
            }
        }
        $activeRequests = $activeRepository->findUserRequestsByFixerId(1);
        return $this->render('fixer/service/active_services.html.twig', [
            'active_requests' => $activeRequests
        ]);
    }

    #[Route('/fixer/active/{id}', name: 'fixer_update_step', methods: ['GET', 'POST'])]
    public function updateStep(Request $request, EntityManagerInterface $em, ServiceStepRepository $serviceStep, RequestActiveRepository $ra): Response
    {
        $id = $request->get('id');
        $activeReq = $ra->find($id);
        $currentStep = $activeReq->getStep();
        $nextStep = $serviceStep->findOneByStepValue($currentStep->getStep() + 1);

        if ($request->getMethod() == 'POST') {
            $activeReq->setStep($nextStep);
            $currentStep = $nextStep;
            $nextStep = $serviceStep->findOneByStepValue($currentStep->getStep() + 1);
            $em->persist($activeReq);
            $em->flush();
        }

        return $this->render('fixer/service/modify_request.html.twig', [
            'current_step' => $currentStep,
            'next_step' => $nextStep,
            'request' => $activeReq
        ]);
    }
}
