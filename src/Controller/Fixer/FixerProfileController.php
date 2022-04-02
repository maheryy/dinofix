<?php

namespace App\Controller\Fixer;

use App\Repository\FixerRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FixerProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'profile', methods: ['GET'])]
    public function getHome(Request $request, FixerRepository $fixerRepository, ServiceRepository $serviceRepository): Response
    {
        $fixer = $fixerRepository->find($request->get('id'));
        $fixerServices = $serviceRepository->findFixerServicesById(1, 10);
        return $this->render('fixer/profile/profile.html.twig', [
            'fixer' => $fixer,
            'fixer_services' => $fixerServices
        ]);
    }
}

