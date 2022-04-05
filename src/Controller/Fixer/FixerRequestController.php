<?php

namespace App\Controller\Fixer;

use App\Entity\RequestActive;
use App\Repository\RequestActiveRepository;
use App\Repository\RequestRepository;
use App\Repository\ServiceRepository;
use App\Repository\ServiceStepRepository;
use App\Service\ResolverService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FixerRequestController extends AbstractController
{

    #[Route('/customer/requests', name: 'requests', methods: ['GET'])]
    public function freeRequests(ResolverService $resolverService, RequestRepository $requestRepository): Response
    {
        $expertise = $resolverService->getFixerExpertise($this->getUser()->getId());
        $freeRequests = !empty($expertise) ? $requestRepository->findFreeRequests(array_keys($expertise['categories']), array_keys($expertise['dinos'])) : [];

        return $this->render('fixer/request/free_request_list.html.twig', [
            'requests' => $freeRequests
        ]);
    }

    #[Route('/customer/request/{slug}', name: 'request', methods: ['GET', 'POST'])]
    public function request(Request $request, string $slug, RequestRepository $requestRepository, ServiceRepository $serviceRepository, EntityManagerInterface $em, ServiceStepRepository $serviceStepRepository): Response
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
            return $this->redirectToRoute('fixer_active');
        }

        $availableServices = $serviceRepository->findFixerRequestRelatedServices($this->getUser()->getId(), $requestEntity->getCategory()?->getId(), $requestEntity->getDino()?->getId());
        return $this->render('fixer/request/request.html.twig', [
            'request' => $requestEntity,
            'available_services' => $availableServices
        ]);
    }

    #[Route('/history', name: 'history', methods: ['GET'])]
    public function getHome(RequestActiveRepository $activeRepository): Response
    {
        $requests = $activeRepository->findFixerDoneRequests($this->getUser()->getId());
        return $this->render('fixer/request/history.html.twig', [
            'requests' => $requests
        ]);
    }
}
