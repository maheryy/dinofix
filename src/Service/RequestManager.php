<?php

namespace App\Service;

use App\Entity\Request;
use App\Entity\RequestActive;
use App\Entity\ServiceStep;
use App\Repository\RequestActiveRepository;
use App\Repository\RequestRepository;
use App\Repository\ServiceStepRepository;
use Doctrine\ORM\EntityManagerInterface;

class RequestManager
{

    public function __construct(
        private EntityManagerInterface  $em,
        private ServiceStepRepository   $serviceStepRepository,
        private RequestRepository       $requestRepository,
        private RequestActiveRepository $requestActiveRepository,
    )
    {
    }

    public function getRequest(string $slug): ?Request
    {
        return $this->requestRepository->findRequestBySlug($slug);
    }

    public function getActiveRequest(string $slug): ?RequestActive
    {
        return $this->requestActiveRepository->findRequestBySlug($slug);
    }

    public function getActiveRequestNextStep(RequestActive $requestActive): ?ServiceStep
    {
        return $this->serviceStepRepository->findOneStepByService($requestActive->getStep()->getService(), $requestActive->getStep()->getStep() + 1);
    }

    public function getActiveRequestLastStep(RequestActive $requestActive): ?ServiceStep
    {
        return $this->serviceStepRepository->findLastStepByService($requestActive->getStep()->getService());
    }

    public function countActiveRequestSteps(RequestActive $requestActive): ?int
    {
        return $this->serviceStepRepository->countStepsByService($requestActive->getStep()->getService());
    }

    public function handleRequestAction(RequestActive $requestActive, string $action): void
    {
        switch ($action) {
            case Constant::ACTION_CONTINUE :
                $nextStep = $this->getActiveRequestNextStep($requestActive);
                if (!$nextStep) {
                    return;
                }
                $requestActive->setStep($nextStep);
                break;

            case Constant::ACTION_PAUSE :
                $requestActive->setStatus(Constant::STATUS_PAUSED);
                break;

            case Constant::ACTION_FINISH :
                $lastStep = $this->getActiveRequestLastStep($requestActive);
                if (!$lastStep) {
                    return;
                }

                $requestActive->setStatus(Constant::STATUS_DONE);
                $requestActive->setStep($lastStep);
                $request = $requestActive->getRequest();
                $request->setStatus(Constant::STATUS_DONE);
                $this->em->persist($request);
                break;

            case Constant::ACTION_CANCEL :
                $requestActive->setStatus(Constant::STATUS_CANCELLED);
                $request = $requestActive->getRequest();
                $request->setStatus(Constant::STATUS_CANCELLED);
                $this->em->persist($request);
                break;
        }

        $this->em->persist($requestActive);
        $this->em->flush();
    }

}