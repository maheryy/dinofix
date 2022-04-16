<?php

namespace App\Controller\Common;

use App\Repository\FixerRepository;
use App\Repository\ReviewRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{
    #[Route('/profile/{slug}', name: 'fixer_profile', methods: ['GET'])]
    public function fixerProfile(string $slug, FixerRepository $fixerRepository, ServiceRepository $serviceRepository, ReviewRepository $reviewRepository): Response
    {
        $fixer = $fixerRepository->findFixerBySlug($slug);
        if (!$fixer) {
            throw new BadRequestHttpException();
        }

        $services = $serviceRepository->findFixerServicesById($fixer->getId());
        $reviews = $reviewRepository->findFixerReviews($fixer->getId());
        return $this->render('common/public/fixer_profile.html.twig', [
            'fixer' => $fixer,
            'reviews' => $reviews,
            'services' => $services,
        ]);
    }
}

