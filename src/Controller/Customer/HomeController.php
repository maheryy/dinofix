<?php

namespace App\Controller\Customer;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'landing')]
    public function landing(ServiceRepository $serviceRepository): Response
    {
        $isLoggedIn = false;
        if ($isLoggedIn) {
            return $this->redirectToRoute('homepage');
        }

        $popularServices = $serviceRepository->findPopularServices(8, 1.5);
        return $this->render('customer/home/landing.html.twig', [
            'popular_services' => $popularServices
        ]);
    }

    #[Route('/home', name: 'homepage')]
    public function home(ServiceRepository $serviceRepository): Response
    {

        return $this->render('customer/home/home.html.twig', [

        ]);
    }


}
