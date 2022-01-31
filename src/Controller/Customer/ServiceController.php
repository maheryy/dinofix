<?php

namespace App\Controller\Customer;

use App\Data\SearchData;
use App\Form\SearchFilterType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->redirectToRoute('customer_search');
    }

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
    public function service(int $id, ServiceRepository $serviceRepository): Response
    {
        try {
            if (!($service = $serviceRepository->findServiceById($id))) {
                throw new \Exception();
            }
        } catch (NonUniqueResultException | \Exception $e) {
            return $this->render('customer/service/search.html.twig', ['services' => []]);
        }

        $reviews = $service->getReviews();
        $reviewData = $this->getReviewAverageData($reviews);
        $otherServices = $serviceRepository->findFixerServices($id, 4);

        $otherServicesReviewData = [];
        foreach ($otherServices as $otherService) {
            $otherServicesReviewData[$otherService->getId()] = $this->getReviewAverageData($otherService->getReviews());
        }

        return $this->render('customer/service/service.html.twig', [
            'service' => $service,
            'reviews' => $reviews,
            'review_data' => $reviewData,
            'other_services' => $otherServices,
            'other_services_review_data' => $otherServicesReviewData,
        ]);
    }

    private function getReviewAverageData($reviews): array
    {
        $reviewCount = count($reviews);
        $reviewAvg = 0;

        foreach ($reviews as $review) {
            $reviewAvg += $review->getRate();
        }

        $reviewAvg = $reviewCount ? round($reviewAvg / $reviewCount, 2) : 0;
        $reviewRoundedAvg = $reviewAvg;

        if (floor($reviewAvg) != $reviewAvg && ceil($reviewAvg) != $reviewAvg) {
            $reviewRoundedAvg = floor($reviewAvg) + 0.5;
        }

        return [
            'count' => $reviewCount,
            'rounded' => $reviewRoundedAvg,
            'average' => $reviewAvg,
        ];
    }
}
