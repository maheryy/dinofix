<?php

namespace App\Controller\Customer;

use App\Entity\Request as RequestEntity;
use App\Repository\RequestRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    public function __toString()
    {
        return $this->requestEntity;
    }

    #[Route('/payment/{slug}', name: 'payment', methods: ['GET'])]
    public function index(string $slug, ServiceRepository $serviceRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $service = $serviceRepository->findServiceBySlug($slug);
        if (!$service) {
            return $this->render('customer/service/search.html.twig', ['services' => []]);
        }

        return $this->render('customer/payment/index.html.twig', [
            'service' => $service,
            'stripe' => $this->getParameter('stripe_public')
        ]);
    }

    #[Route('/checkout/{slug}', name: 'checkout', methods: ['POST'])]
    public function checkout(Request $request, string $slug, EntityManagerInterface $entityManager, ServiceRepository $serviceRepository, RequestRepository $requestRepository): Response
    {
        $token = $request->request->get('stripeToken');
        \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret'));
        $charge = \Stripe\Charge::create([
            "amount" => 50 * 100,
            "currency" => "eur",
            "source" => $token,
            "description" => "Paiement service"
        ]);
        if ($charge) {
            $service = $serviceRepository->findServiceBySlug($slug);
            if (!$service) {
                return $this->render('customer/service/search.html.twig', ['services' => []]);
            }
            $requestEntity = new RequestEntity();
            $reference = $requestRepository->generateReference();
            $datetime = new \DateTime('now');
            $user = $this->getUser();
            $requestEntity->setCustomer($user)
            ->setReference($reference)
            ->setStatus(1)
            ->setService($service)
            ->setCategory($service->getCategory())
            ->setDino($service->getDino())
            ->setSubject($service->getName())
            ->setDescription($service->getDescription())
            ->setExpectedAt($datetime);
    
            $entityManager->persist($requestEntity);
            $entityManager->flush();
    
            return $this->redirectToRoute('customer_request_index', [], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('service');
        }
    }
}
