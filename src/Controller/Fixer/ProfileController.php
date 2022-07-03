<?php

namespace App\Controller\Fixer;

use App\Entity\Fixer;
use App\Form\FixerType;
use App\Repository\FixerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/{id}/edit', name: 'profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fixer $fixer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FixerType::class, $fixer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('picture')) {
                $file = $form->get('picture')->getData();
                $fileName = $this->getUser()->getFirstName().'-'.$this->getUser()->getLastName().'-'.uniqid() . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('fixer_pictures_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $fixer->setPicture($fileName);
            }
            $entityManager->flush();

            $this->addFlash('success', 'Vos informations ont été modifiées avec succés !');
            return $this->redirectToRoute('fixer_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fixer/profile/edit.html.twig', [
            'fixer' => $fixer,
            'form' => $form,
        ]);
    }
}
