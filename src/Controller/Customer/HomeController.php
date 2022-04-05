<?php

namespace App\Controller\Customer;

use App\Repository\CategoryRepository;
use App\Repository\RequestActiveRepository;
use App\Repository\ServiceRepository;
use App\Service\Constant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function home(ServiceRepository $serviceRepository, CategoryRepository $categoryRepository, RequestActiveRepository $requestActiveRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('homepage');
        }

        $popularServices = $serviceRepository->findPopularServices(12, 1.5);
        $activeServices = $requestActiveRepository->findUserRequestsByStatus($user->getId(), Constant::STATUS_DEFAULT);
        $pastServices = $requestActiveRepository->findUserRequestsByStatus($user->getId(), Constant::STATUS_DONE);
        $categories = $categoryRepository->findPopularCategories(4);
        return $this->render('customer/home/home.html.twig', [
            'popular_services' => $popularServices,
            'active_services' => $activeServices,
            'past_services' => $pastServices,
            'categories' => $categories,
        ]);
    }


}
