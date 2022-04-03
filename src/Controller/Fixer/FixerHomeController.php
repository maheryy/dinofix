<?php

namespace App\Controller\Fixer;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FixerHomeController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function getHome(ServiceRepository $serviceRepository): Response
    {
        $fixerServices = $serviceRepository->findFixerServicesById($this->getUser()->getId(), 10);
        return $this->render('fixer/home/home.html.twig', [
            'fixer_services' => $fixerServices
        ]);
    }
}
