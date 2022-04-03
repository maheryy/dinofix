<?php

namespace App\Controller\Fixer;

use App\Repository\FixerRepository;
use App\Repository\ServiceRepository;
use App\Form\ServiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Service;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class FixerServicesController extends AbstractController
{
    #[Route('/services/new', name: 'services_create', methods: ['GET', 'POST'])]
    public function createService(Request $request, EntityManagerInterface $entityManager, FixerRepository $fixerRepository): Response
    {
        $service = new Service();
        $service->setFixer($this->getUser())
                ->setRating(0);

        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('fixer_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('fixer/service/services.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/services/{id}', name: 'services_edit', methods: ['GET', 'POST'])]
    public function editService(Request $request, ServiceRepository $serviceRepository, EntityManagerInterface $entityManager): Response
    {
        $service = $serviceRepository->find($request->get('id'));
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('fixer_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('fixer/service/services.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

}
