<?php

namespace App\Controller\Fixer;

use App\Repository\RequestActiveRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FixerActiveController extends AbstractController
{
    #[Route('/fixer/active', name: 'fixer_active', methods: ['GET'])]
    public function getHome(RequestActiveRepository $activeRepository): Response
    {
        // $activeRequests = $activeRepository->findUserRequestsByFixerId(1);
        // dump($activeRequests);
        return $this->render('fixer/service/active_services.html.twig');
    }
}
