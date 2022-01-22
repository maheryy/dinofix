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
        $searchData->page = $request->get('page', 1);

        $filterForm = $this->createForm(SearchFilterType::class, $searchData);
        $filterForm->handleRequest($request);

        $services = $serviceRepository->findAllBySearch($searchData);

        $params = [
            'services' => $services,
            'previousQuery' => $searchData->query,
            'form' => $filterForm->createView()
        ];
        return $this->render('customer/service/search.html.twig', $params);
    }
}
