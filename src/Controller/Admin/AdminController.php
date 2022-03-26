<?php

namespace App\Controller\Admin;

use App\Entity\Dino;
use App\Form\DinoType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Admin;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\CustomerRepository;
use App\Repository\FixerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{
    #[Route('admin', name: 'user_list')]
    public function index(FixerRepository $fixerRepository, CustomerRepository $customerRepository): Response
    {

        $customer = $customerRepository->findAllCustomer();
        $fixer = $fixerRepository->findAllFixer();
        $allUser = array_merge($fixer,$customer );

        return $this->render('admin/user_list/admin.html.twig', [
            'controller_name' => 'AdminController',
            'allUser' => $allUser,
        ]);
    }

    #[Route('addcategory', name: 'addCategory')]
    public function addCategory(Request $request, EntityManagerInterface $entityManager ): Response
    {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $category->setPicture('no pic');
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('user_list');
        }



        return $this->render('admin/add_category/add_category.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/', name: 'service_index', methods: ['GET'])]
    public function listService(ServiceRepository $serviceRepository): Response
    {
        return $this->render('admin/service_list/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }

    #[Route('adddino', name: 'addDino')]
    public function addDino(Request $request, EntityManagerInterface $entityManager ): Response
    {

        $dino = new Dino();
        $form = $this->createForm(DinoType::class, $dino );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $dino->setPicture('no pic');
            $entityManager->persist($dino);
            $entityManager->flush();

            return $this->redirectToRoute('user_list');
        }



        return $this->render('admin/add_dino/add_dino.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
