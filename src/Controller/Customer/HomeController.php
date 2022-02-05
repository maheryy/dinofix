<?php

namespace App\Controller\Customer;

use App\Repository\CategoryRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    #[Route('/', name: 'landing')]
    public function landing(Security $security, ServiceRepository $serviceRepository): Response
    {
        // Logged user is redirected to home page
        if ($security->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        $popularServices = $serviceRepository->findPopularServices(8, 1.5);

        return $this->render('customer/home/landing.html.twig', [
            'popular_services' => $popularServices
        ]);
    }

    #[Route('/home', name: 'homepage')]
    public function home(ServiceRepository $serviceRepository, CategoryRepository $categoryRepository): Response
    {
        $popularServices = $serviceRepository->findPopularServices(12, 1.5);
        $randomActiveServices = $serviceRepository->findRandomServices(3);
        $randomPastServices = $serviceRepository->findRandomServices(4, 'rating');
        $categories = $categoryRepository->findPopularCategories(4);

        return $this->render('customer/home/home.html.twig', [
            'popular_services' => $popularServices,
            'active_services' => $randomActiveServices,
            'past_services' => $randomPastServices,
            'categories' => $categories,
            'user' => ['firstname' => 'John'],
            //'user' => $security->getUser(),
        ]);
    }


}
