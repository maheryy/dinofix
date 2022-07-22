<?php

namespace App\Controller\Customer;

use App\Entity\Request as RequestEntity;
use App\Repository\RequestRepository;
use App\Repository\ServiceRepository;
use App\Service\Constant;
use App\Service\RequestManager;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    #[Route('/service/{slug}/checkout', name: 'checkout', methods: ['GET'])]
    public function checkout(string $slug, ServiceRepository $serviceRepository,): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $service = $serviceRepository->findServiceBySlug($slug);
        if (!$service) {
            throw new BadRequestHttpException();
        }

        try {
            Stripe::setApiKey($this->getParameter('stripe_secret'));
            $session = Session::create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $service->getName(),
                        ],
                        'unit_amount' => $service->getPrice() * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => urldecode($this->generateUrl('customer_payment_success', ['slug' => $slug, 'session_id' => "{CHECKOUT_SESSION_ID}"], UrlGeneratorInterface::ABSOLUTE_URL)),
                'cancel_url' => $this->generateUrl('customer_service', ['slug' => $slug], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

            return $this->redirect($session->url);
        } catch (ApiErrorException $errorException) {
            throw new \Exception($errorException->getMessage());
        }
    }

    #[Route('/service/{slug}/checkout/success', name: 'payment_success', methods: ['GET'])]
    public function success(Request $request, string $slug, ServiceRepository $serviceRepository, RequestRepository $requestRepository, RequestManager $requestManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $service = $serviceRepository->findServiceBySlug($slug);
        $paymentSession = $request->get('session_id');
        if (!$service || !$paymentSession) {
            throw new BadRequestHttpException();
        }

        try {
            Stripe::setApiKey($this->getParameter('stripe_secret'));
            Session::retrieve($paymentSession);
        } catch (ApiErrorException $errorException) {
            throw new BadRequestHttpException();
        }

        if (!($newRequest = $requestRepository->findPaidRequest($this->getUser(), $service, $paymentSession))) {
            $requestEntity = (new RequestEntity())
                ->setCustomer($this->getUser())
                ->setService($service)
                ->setCategory($service->getCategory())
                ->setDino($service->getDino())
                ->setPaymentReference($paymentSession)
                ->setStatus(Constant::STATUS_ACTIVE)
                ->setSubject("{$this->getUser()->getFirstname()} - {$service->getName()}")
                ->setDescription('Service payé');

            $newRequest = $requestManager->createPaidRequest($requestEntity);
        }
        return $this->render('customer/payment/success.html.twig', [
            'service' => $service,
            'request' => $newRequest
        ]);
    }
}
