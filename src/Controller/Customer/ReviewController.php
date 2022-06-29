<?php

namespace App\Controller\Customer;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use App\Repository\ServiceRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/review')]
class ReviewController extends AbstractController
{
    #[Route('/', name: 'review_index', methods: ['GET'])]
    public function index(ReviewRepository $reviewRepository): Response
    {
        return $this->render('customer/review/index.html.twig', [
            'reviews' => $reviewRepository->findAll(),
        ]);
    }

    #[Route('/new/{slug}/', name: 'review_new', methods: ['GET', 'POST'])]
    public function new($slug ,Request $request, EntityManagerInterface $entityManager, ServiceRepository $serviceRepository, ReviewRepository $reviewRepository): Response
    {
        $service = $serviceRepository->findServiceBySlug($slug);
        if (!$service) {
            throw new NotFoundHttpException();
        }

            $review = new Review();

            //$service = $serviceRepository->find($id);
            //$service->addReview($review);

            $form = $this->createForm(ReviewType::class, $review);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $review = $form->getData();
                $review
                    ->setCustomer($this->getUser())
                    ->setStatus(0)  
                    ->setService($service)
                                                          
                ;

                $entityManager->persist($review);
                $entityManager->flush();

                //$service = $serviceRepository->find($id);
                $service->addReview($review);

                return $this->redirectToRoute('homepage');
            }
            //dd($review);
            return $this->render('customer/review/new.html.twig', [
                //'review' => $review,
                'form' => $form->createView(),

            ]);
        
    }

    #[Route('/{id}', name: 'review_show', methods: ['GET'])]
    public function show(Review $review): Response
    {
        return $this->render('customer/review/show.html.twig', [
            'review' => $review,
        ]);
    }

    #[Route('/{id}/edit', name: 'review_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Review $review, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('customer_review_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customer/review/edit.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'review_delete', methods: ['POST'])]
    public function delete(Request $request, Review $review, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $review->getId(), $request->request->get('_token'))) {
            $entityManager->remove($review);
            $entityManager->flush();
        }

        return $this->redirectToRoute('customer_review_index', [], Response::HTTP_SEE_OTHER);
    }
}
