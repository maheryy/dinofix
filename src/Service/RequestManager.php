<?php

namespace App\Service;

use App\Entity\Request;
use App\Entity\RequestActive;
use App\Entity\RequestLog;
use App\Entity\Service;
use App\Entity\ServiceStep;
use App\Repository\RequestActiveRepository;
use App\Repository\RequestLogRepository;
use App\Repository\RequestRepository;
use App\Repository\ServiceStepRepository;
use Doctrine\ORM\EntityManagerInterface;

class RequestManager
{

    public function __construct(
        private Generator               $generator,
        private EntityManagerInterface  $em,
        private ServiceStepRepository   $serviceStepRepository,
        private RequestRepository       $requestRepository,
        private RequestActiveRepository $requestActiveRepository,
        private RequestLogRepository    $requestLogRepository,
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
        return $this->serviceStepRepository->findOneStepByService($requestActive->getStep()?->getService(), $requestActive->getStep()?->getStep() + 1);
    }

    public function getActiveRequestLastStep(RequestActive $requestActive): ?ServiceStep
    {
        return $this->serviceStepRepository->findLastStepByService($requestActive->getStep()?->getService());
    }

    public function getActiveRequestAllSteps(RequestActive $requestActive): array
    {
        return $this->serviceStepRepository->findStepsByService($requestActive->getStep()?->getService());
    }

    public function getRequestLogs(Request $request): array
    {
        return $this->requestLogRepository->findAllRequestLog($request);
    }

    public function countActiveRequestSteps(RequestActive $requestActive): ?int
    {
        return $this->serviceStepRepository->countStepsByService($requestActive->getStep()?->getService());
    }

    public function createOpenRequest(Request $request)
    {
        $request->setReference($this->generator->generateRequestReference());

        $this->saveLog($request, 'Création de la demande');

        $this->em->persist($request);
        $this->em->flush();
    }

    public function acceptOpenRequest(Request $request, Service $service)
    {
        $request
            ->setService($service)
            ->setStatus(Constant::STATUS_ACTIVE);

        $serviceStep = $this->serviceStepRepository->countStepsByService($service) ? $service : null;

        $requestActive = (new RequestActive())
            ->setFixer($service->getFixer())
            ->setRequest($request)
            ->setStep($this->serviceStepRepository->findFirstStepByService($serviceStep));

        $this->saveLog($request, 'Demande prise en charge');

        $this->em->persist($request);
        $this->em->persist($requestActive);
        $this->em->flush();
    }

    public function createPaidRequest(Request $request)
    {
        $service = $request->getService();
        $serviceStep = $this->serviceStepRepository->countStepsByService($service) ? $service : null;

        $request->setReference($this->generator->generateRequestReference());
        $requestActive = (new RequestActive())
            ->setRequest($request)
            ->setFixer($service->getFixer())
            ->setStep($this->serviceStepRepository->findFirstStepByService($serviceStep));

        $this->saveLog($request, 'Création de la demande');

        $this->em->persist($request);
        $this->em->persist($requestActive);
        $this->em->flush();
    }

    public function handleRequestAction(RequestActive $requestActive, string $action): void
    {
        $request = $requestActive->getRequest();
        $event = null;

        switch ($action) {
            case Constant::ACTION_CONTINUE :
                $nextStep = $this->getActiveRequestNextStep($requestActive);
                if (!$nextStep) {
                    return;
                }

                $event = $requestActive->getStatus() === Constant::STATUS_DEFAULT ? "Début de l'intervention" : "Passage à l'étape \"{$nextStep->getName()}\"";
                $requestActive->setStep($nextStep);
                $requestActive->setStatus(Constant::STATUS_ACTIVE);
                break;

            case Constant::ACTION_PAUSE :
                $event = 'Intervention suspendu';
                $requestActive->setStatus(Constant::STATUS_PAUSED);
                break;

            case Constant::ACTION_FINISH :
                $lastStep = $this->getActiveRequestLastStep($requestActive);
                if (!$lastStep) {
                    return;
                }

                $event = 'Intervention terminée';
                $requestActive->setStatus(Constant::STATUS_DONE);
                $requestActive->setStep($lastStep);
                $request->setStatus(Constant::STATUS_DONE);
                $this->em->persist($request);
                break;

            case Constant::ACTION_CANCEL :
                $event = 'Intervention annulée';
                $requestActive->setStatus(Constant::STATUS_CANCELLED);
                $request->setStatus(Constant::STATUS_CANCELLED);
                $this->em->persist($request);
                break;
        }

        $this->saveLog($request, $event);
        $this->em->persist($requestActive);
        $this->em->flush();
    }

    private function saveLog(Request $request, string $event)
    {
        $requestLog = (new RequestLog())
            ->setRequest($request)
            ->setEvent($event);

        $this->em->persist($requestLog);
    }


}