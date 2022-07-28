<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ProfileController extends AbstractController
{
    #[Route('/account/edit', name: 'profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserPasswordHasherInterface $customerPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $customer = $this->getUser();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $customer->setPassword(
                $customerPasswordHasher->hashPassword(
                    $customer,
                    $form->get('password')->getData()
                )
            );
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été modifié avec succés !');
            return $this->redirectToRoute('customer_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customer/profile/edit.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }
}
