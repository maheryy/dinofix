<?php

namespace App\Controller\Customer;

use App\Data\SearchData;
use App\Form\SearchFilterType;
use App\Repository\ReviewRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request, ServiceRepository $serviceRepository): Response
    {
        $searchData = new SearchData();
        $searchData->setPage($request->get('page', 1));

        $filterForm = $this->createForm(SearchFilterType::class, $searchData);
        $filterForm->handleRequest($request);

        $services = $serviceRepository->findAllBySearch($searchData, 10);

        return $this->render('customer/service/search.html.twig', [
            'services' => $services,
            'previousQuery' => $searchData->getQuery(),
            'previousLocation' => $searchData->getLocation(),
            'form' => $filterForm->createView(),
        ]);
    }

    #[Route('/service/{slug}', name: 'service', methods: ['GET'])]
    public function service(string $slug, ServiceRepository $serviceRepository, ReviewRepository $reviewRepository): Response
    {
        $service = $serviceRepository->findServiceBySlug($slug);
        if (!$service) {
            throw new NotFoundHttpException();
        }

        $reviews = $reviewRepository->findServiceReviews($service->getId());
        $otherServices = $serviceRepository->findFixerServices($service->getFixer()->getId(), $service->getId(), 4);

        return $this->render('customer/service/service.html.twig', [
            'service' => $service,
            'reviews' => $reviews,
            'other_services' => $otherServices,
        ]);
    }

}
