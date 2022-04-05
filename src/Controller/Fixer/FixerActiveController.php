<?php

namespace App\Controller\Fixer;

use App\Entity\ServiceStep;
use App\Repository\RequestActiveRepository;
use App\Repository\RequestRepository;
use App\Repository\ServiceRepository;
use App\Repository\ServiceStepRepository;
use App\Service\Constant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FixerActiveController extends AbstractController
{
    #[Route('/active', name: 'active', methods: ['GET', 'POST'])]
    public function getHome(RequestActiveRepository $activeRepository, Request $request, EntityManagerInterface $em, ServiceStepRepository $serviceStepRepository): Response
    {
        if ($request->getMethod() == 'POST') {
            $id = $request->request->get('id');
            $acceptedReq = $activeRepository->find($id);
            $acceptedReq->setStep($serviceStepRepository->findOneBy(['step' => 1]));
            $em->persist($acceptedReq);
            $em->flush();
        }

        $activeRequests = $activeRepository->findUserRequestsByFixerId($this->getUser()->getId());
        return $this->render('fixer/service/active_services.html.twig', [
            'active_requests' => $activeRequests
        ]);
    }

    #[Route('/active/{id}', name: 'update_step', methods: ['GET', 'POST'])]
    public function updateStep(int $id, Request $request, EntityManagerInterface $em, ServiceStepRepository $serviceStep, RequestActiveRepository $ra): Response
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

        return $this->render('fixer/service/modify_request.html.twig', [
            'current_step' => $currentStep,
            'next_step' => $nextStep,
            'request' => $activeReq
        ]);
    }
}
