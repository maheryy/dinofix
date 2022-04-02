<?php

namespace App\Controller\Customer;

use App\Repository\CategoryRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function home(ServiceRepository $serviceRepository, CategoryRepository $categoryRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

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
