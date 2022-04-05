<?php

namespace App\Controller\Fixer;

use App\Repository\RequestActiveRepository;
use App\Repository\RequestRepository;
use App\Service\ResolverService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FixerHomeController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function getHome(ResolverService $resolverService, RequestActiveRepository $requestActiveRepository, RequestRepository $requestRepository): Response
    {
        $expertise = $resolverService->getFixerExpertise($this->getUser()->getId());
        $activeRequests = $requestActiveRepository->findUserRequestsByFixerId($this->getUser()->getId());
        $customerRequests = !empty($expertise) ? $requestRepository->findFreeRequests(array_keys($expertise['categories']), array_keys($expertise['dinos'])) : [];

        return $this->render('fixer/home/home.html.twig', [
            'active_requests' => $activeRequests,
            'customer_requests' => $customerRequests,
        ]);
    }
}
