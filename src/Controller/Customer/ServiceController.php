<?php

namespace App\Controller\Customer;

use App\Data\SearchData;
use App\Entity\Service;
use App\Form\SearchFilterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request): Response
    {
        $serviceRepository = $this->getDoctrine()->getRepository(Service::class);

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
