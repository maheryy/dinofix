<?php

namespace App\Controller\Customer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/service', name: 'service')]
    public function index(): Response
    {
        return $this->render('customer/service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }
}
