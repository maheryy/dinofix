<?php

namespace App\Controller\Customer;

use App\Data\SearchData;
use App\Form\SearchFilterType;
use App\Repository\ServiceRepository;
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
}
