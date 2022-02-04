<?php

namespace App\Controller\Customer;

use App\Data\SearchData;
use App\Form\SearchFilterType;
use App\Repository\ReviewRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

        $services = $serviceRepository->findAllBySearch($searchData);

        return $this->render('customer/service/search.html.twig', [
            'services' => $services,
            'previousQuery' => $searchData->getQuery(),
            'form' => $filterForm->createView(),
        ]);
    }

    #[Route('/service/{id}', name: 'service', methods: ['GET'])]
    public function service(int $id, ServiceRepository $serviceRepository, ReviewRepository $reviewRepository): Response
    {
        try {
            if (!($service = $serviceRepository->findServiceById($id))) {
                throw new \Exception();
            }
        } catch (NonUniqueResultException | \Exception $e) {
            return $this->render('customer/service/search.html.twig', ['services' => []]);
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
