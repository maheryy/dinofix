<?php

namespace App\Controller\Customer;

use App\Entity\Service;
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
        $searchQuery = $request->get('query');
        $services = $serviceRepository->findAllBySearchTerm($searchQuery);

        $params = [
            'services' => $services,
            'previousQuery' => $searchQuery
        ];
        return $this->render('customer/service/search.html.twig', $params);
    }
}
