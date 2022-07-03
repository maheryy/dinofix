<?php

namespace App\Controller\Customer;

use App\Entity\Request as RequestEntity;
use App\Entity\RequestActive;
use App\Repository\RequestRepository;
use App\Repository\ServiceRepository;
use App\Repository\ServiceStepRepository;
use App\Service\Constant;
use App\Service\Generator;
use App\Service\RequestManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    #[Route('/service/{slug}/payment', name: 'payment', methods: ['GET'])]
    public function index(string $slug, ServiceRepository $serviceRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $service = $serviceRepository->findServiceBySlug($slug);
        if (!$service) {
            throw new BadRequestHttpException();
        }

        return $this->render('customer/payment/index.html.twig', [
            'service' => $service,
            'stripe' => $this->getParameter('stripe_public')
        ]);
    }

    #[Route('/service/{slug}/checkout', name: 'checkout', methods: ['POST'])]
    public function checkout(Request $request, string $slug, ServiceRepository $serviceRepository, RequestManager $requestManager): Response
    {
        $service = $serviceRepository->findServiceBySlug($slug);
        if (!$service) {
            throw new BadRequestHttpException();
        }
        $token = $request->request->get('stripeToken');
        \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret'));
        $charge = \Stripe\Charge::create([
            "amount" => $service->getPrice() * 100,
            "currency" => "eur",
            "source" => $token,
            "description" => "Paiement service"
        ]);

        if ($charge) {
            $requestEntity = (new RequestEntity())
            ->setCustomer($this->getUser())
            ->setService($service)
            ->setCategory($service->getCategory())
            ->setDino($service->getDino())
            ->setStatus(Constant::STATUS_ACTIVE)
            ->setSubject("{$this->getUser()->getFirstname()} - {$service->getName()}")
            ->setDescription('Service payé')
            ->setExpectedAt(new \DateTime('now'));

            $requestManager->createPaidRequest($requestEntity);

            return $this->redirectToRoute('customer_request_active', [], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('service');
        }
    }
}
