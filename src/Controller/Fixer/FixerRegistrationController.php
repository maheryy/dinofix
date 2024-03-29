<?php

namespace App\Controller\Fixer;

use App\Entity\Fixer;
use App\Form\FixerRegistrationType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class FixerRegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('GET') && $this->getUser()) {
            return $this->redirectToRoute('fixer_home');
        }

        $user = new Fixer();
        $form = $this->createForm(FixerRegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fakeAddress = (new \App\Entity\Address())
                ->setStreet('4 Rue de l\'Entente')
                ->setCity('Athis-mons')
                ->setRegion('Île-de-France')
                ->setPostcode('91200')
                ->setCountry('France')
                ->setLatitude(48.70560223684846)
                ->setLongitude(2.362543754878404);

            if ($form->get('picture')) {
                $file = $form->get('picture')->getData();
                $fileName = $form->get('firstname')->getData().'-'.$form->get('lastname')->getData().'-'.uniqid() . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('fixer_pictures_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $user->setPicture($fileName);
                $user->setRoles(['ROLE_FIXER']);
            }

            $user
                ->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                )
                ->setAddress($fakeAddress);
            $entityManager->persist($fakeAddress);
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('fixer_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('contact.dinofix@gmail.com', 'Dinofix'))
                    ->to($user->getEmail())
                    ->subject('Confirmation de votre adresse email')
                    ->htmlTemplate('fixer/registration/confirmation_email.html.twig')
            );

            return $this->redirectToRoute('fixer_login');
        }

        return $this->render('fixer/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('fixer_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('fixer_register');
    }
}
