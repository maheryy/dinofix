<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Entity\Review;
use App\Form\Service1Type;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/service')]
class AdminServiceController extends AbstractController
{
    #[Route('/', name: 'service_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('admin/admin_service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $service = new Service();
        $form = $this->createForm(Service1Type::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_service/new.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'service_show', methods: ['GET'])]
    public function show(Service $service): Response
    {
        return $this->render('admin/admin_service/show.html.twig', [
            'service' => $service,
        ]);
    }

    #[Route('/{id}/edit', name: 'service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Service1Type::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, Review $review, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $reviews = $service->getReviews();
            foreach($reviews as $a) {
                
                $entityManager->remove($a);
            }
            
            $entityManager->remove($service);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_service_index', [], Response::HTTP_SEE_OTHER);
    }
}
