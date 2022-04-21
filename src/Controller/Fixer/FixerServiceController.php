<?php

namespace App\Controller\Fixer;

use App\Entity\ServiceStep;
use App\Repository\FixerRepository;
use App\Repository\RequestActiveRepository;
use App\Repository\ServiceRepository;
use App\Form\ServiceType;
use App\Repository\ServiceStepRepository;
use App\Service\Helper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Service;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class FixerServiceController extends AbstractController
{
    #[Route('/service/new', name: 'services_create', methods: ['GET', 'POST'])]
    public function createService(Request $request, EntityManagerInterface $entityManager, FixerRepository $fixerRepository): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->setFixer($this->getUser())
                ->setRating(0);
            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('fixer_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('fixer/service/service_edit.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/service/{slug}', name: 'services_edit', methods: ['GET', 'POST'])]
    public function editService(Request $request, string $slug, ServiceRepository $serviceRepository, EntityManagerInterface $entityManager): Response
    {
        $service = $serviceRepository->findServiceBySlug($slug);
        if (!$service) {
            throw new BadRequestHttpException();
        }

        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('fixer_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('fixer/service/service_edit.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/service', name: 'services', methods: ['GET'])]
    public function listService(ServiceRepository $serviceRepository): Response
    {
        $fixerServices = $serviceRepository->findFixerServicesById($this->getUser()->getId(), 10);

        return $this->render('fixer/service/services.html.twig', [
            'fixer_services' => $fixerServices
        ]);
    }

    #[Route('/service/{slug}/step', name: 'service_step', methods: ['GET', 'POST'])]
    public function editStep(Request $request, string $slug, ServiceRepository $serviceRepository, ServiceStepRepository $serviceStepRepository, RequestActiveRepository $requestActiveRepository, Helper $helper, EntityManagerInterface $entityManager): Response
    {
        $service = $serviceRepository->findServiceBySlug($slug);

        $linkedServiceStep = $serviceStepRepository->countStepsByService($service) ? $service : null;
        $steps = $serviceStepRepository->findStepsByService($linkedServiceStep);
        $hasActiveRequests = $requestActiveRepository->countActiveRequestsByService($service);
        dump($hasActiveRequests);
        if ($request->isMethod('POST') && !$hasActiveRequests) {
            $dataSteps = $helper->buildArrayFromKeyCombination($request->request->all('steps'));

            if ($linkedServiceStep) {
                $serviceStepRepository->removeStepsByService($linkedServiceStep);
            }

            foreach ($dataSteps as $key => $step) {
                $serviceStepEntity = (new ServiceStep())
                    ->setStep($key + 1)
                    ->setName($step['name'])
                    ->setDescription($step['description'])
                    ->setService($service);

                $entityManager->persist($serviceStepEntity);
            }

            $entityManager->flush();
            $steps = $serviceStepRepository->findStepsByService($service);
        }

        return $this->render('fixer/service/step.html.twig', [
            'steps' => $steps,
            'service' => $service,
            'has_active_requests' => (bool)$hasActiveRequests
        ]);
    }
}
