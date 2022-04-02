<?php

namespace App\Controller\Common;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function landing(ServiceRepository $serviceRepository): Response
    {
        $popularServices = $serviceRepository->findPopularServices(8, 1.5);

        return $this->render('customer/home/landing.html.twig', [
            'popular_services' => $popularServices
        ]);
    }

}
