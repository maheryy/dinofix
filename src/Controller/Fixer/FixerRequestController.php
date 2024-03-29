<?php

namespace App\Controller\Fixer;

use App\Repository\RequestActiveRepository;
use App\Repository\RequestRepository;
use App\Repository\ServiceRepository;
use App\Service\RequestManager;
use App\Service\ResolverService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class FixerRequestController extends AbstractController
{
    #[Route('/open/request', name: 'open_requests', methods: ['GET'])]
    public function openRequestList(ResolverService $resolverService, RequestRepository $requestRepository): Response
    {
        $expertise = $resolverService->getFixerExpertise($this->getUser()->getId());
        $freeRequests = !empty($expertise) ? $requestRepository->findFreeRequests(array_keys($expertise['categories']), array_keys($expertise['dinos'])) : [];

        return $this->render('fixer/request/free_request_list.html.twig', [
            'requests' => $freeRequests
        ]);
    }

    #[Route('/open/request/{slug}', name: 'open_request', methods: ['GET', 'POST'])]
    public function openRequest(Request $request, string $slug, RequestRepository $requestRepository, ServiceRepository $serviceRepository, RequestManager $requestManager): Response
    {
        $requestEntity = $requestRepository->findRequestBySlug($slug);

        if (!$requestEntity) {
            throw new BadRequestHttpException();
        }

        if ($request->isMethod('POST')) {
            $service = $serviceRepository->find($request->request->get('service'));
            $requestManager->acceptOpenRequest($requestEntity, $service);

            return $this->redirectToRoute('fixer_requests');
        }

        $availableServices = $serviceRepository->findFixerRequestRelatedServices($this->getUser(), $requestEntity->getCategory()?->getId(), $requestEntity->getDino()?->getId());
        return $this->render('fixer/request/free_request.html.twig', [
            'request' => $requestEntity,
            'available_services' => $availableServices
        ]);
    }

    #[Route('/request', name: 'requests', methods: ['GET'])]
    public function requestList(RequestActiveRepository $activeRepository): Response
    {
        $activeRequests = $activeRepository->findActiveRequestsByFixer($this->getUser());
        return $this->render('fixer/request/request_list.html.twig', [
            'active_requests' => $activeRequests
        ]);
    }

    #[Route('/request/{slug}', name: 'request', methods: ['GET'])]
    public function request(string $slug, RequestManager $requestManager): Response
    {
        $activeRequest = $requestManager->getActiveRequest($slug);
        $this->denyAccessUnlessGranted('VIEW', $activeRequest);
        if (!$activeRequest) {
            throw new BadRequestHttpException();
        }

        $currentStep = $activeRequest->getStep();
        $nextStep = $requestManager->getActiveRequestNextStep($activeRequest);
        $steps = $requestManager->getActiveRequestAllSteps($activeRequest);
        $logs = $requestManager->getRequestLogs($activeRequest->getRequest());

        return $this->render('fixer/request/request.html.twig', [
            'active_request' => $activeRequest,
            'current_step' => $currentStep,
            'next_step' => $nextStep,
            'logs' => $logs,
            'steps' => $steps,
        ]);
    }

    #[Route('/request/{slug}', name: 'request_action', methods: ['POST'])]
    public function requestAction(Request $request, string $slug, RequestManager $requestManager, MailerInterface $mail): Response
    {
        $activeRequest = $requestManager->getActiveRequest($slug);
        $this->denyAccessUnlessGranted('EDIT', $activeRequest);
        if (!$activeRequest) {
            throw new BadRequestHttpException();
        }

        if ($activeRequest->getStep()->getNotify()) {
            $notify = (new TemplatedEmail())
                ->from(new Address('contact.dinofix@gmail.com', 'Dinofix'))
                ->to($activeRequest->getRequest()->getCustomer()->getEmail())
                ->subject('Mise à jour de votre demande !')
                ->htmlTemplate('fixer/service/step_email.html.twig')
                ->context([
                    'step' => $activeRequest->getStep()->getName().' - '. $activeRequest->getStep()->getDescription(),
                    'fixer' => $activeRequest->getFixer()->getLastName().' '.$activeRequest->getFixer()->getFirstName(),
                    'reference' => $activeRequest->getRequest()->getReference(),
                ]);
            $mail->send($notify);
            $this->addFlash('success', 'Une notification a été envoyée au client.');
        }
        $requestManager->handleRequestAction($activeRequest, $request->request->get('action'));

        return $this->redirectToRoute('fixer_request', ['slug' => $slug]);
    }

    #[Route('/history', name: 'history', methods: ['GET'])]
    public function history(RequestActiveRepository $activeRepository): Response
    {
        $requests = $activeRepository->findFixerDoneRequests($this->getUser());
        return $this->render('fixer/request/history.html.twig', [
            'requests' => $requests
        ]);
    }
}
