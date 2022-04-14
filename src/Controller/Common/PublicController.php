<?php

namespace App\Controller\Common;

use App\Repository\FixerRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{
    #[Route('/profile/{slug}', name: 'fixer_profile', methods: ['GET'])]
    public function fixerProfile(string $slug, FixerRepository $fixerRepository, ServiceRepository $serviceRepository): Response
    {
        $fixer = $fixerRepository->findFixerBySlug($slug);

        if (!$fixer) {
            throw new BadRequestHttpException();
        }

        $fixerServices = $serviceRepository->findFixerServicesById($fixer->getId(), 10);
        return $this->render('common/public/fixer_profile.html.twig', [
            'fixer' => $fixer,
            'fixer_services' => $fixerServices
        ]);
    }
}

