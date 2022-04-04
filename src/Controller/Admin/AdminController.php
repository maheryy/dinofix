<?php

namespace App\Controller\Admin;

use App\Entity\Dino;
use App\Form\DinoType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CustomerRepository;
use App\Repository\FixerRepository;
use App\Repository\RequestActiveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(FixerRepository $fixerRepository, CustomerRepository $customerRepository): Response
    {
        $customer = $customerRepository->findAllCustomer();
        $fixer = $fixerRepository->findAllFixer();
        $allUser = array_merge($fixer, $customer);

        return $this->render('admin/user_list/admin.html.twig', [
            'controller_name' => 'AdminController',
            'allUser' => $allUser,
        ]);
    }

    #[Route('/user/list', name: 'user_list', methods: ['GET'])]
    public function userList(FixerRepository $fixerRepository, CustomerRepository $customerRepository): Response
    {
        $customer = $customerRepository->findAllCustomer();
        $fixer = $fixerRepository->findAllFixer();
        $allUser = array_merge($fixer, $customer);

        return $this->render('admin/user_list/admin.html.twig', [
            'controller_name' => 'AdminController',
            'allUser' => $allUser,
        ]);
    }

    #[Route('/category/new', name: 'category_new', methods: ['GET', 'POST'])]
    public function addCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $category->setPicture('no pic');
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/add_category/add_category.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/service/list', name: 'service_list', methods: ['GET'])]
    public function listService(ServiceRepository $serviceRepository): Response
    {
        return $this->render('admin/service_list/index.html.twig', [
            'services' => $serviceRepository->findServicesDashboard(),
        ]);
    }

    #[Route('/active/list', name: 'active_list', methods: ['GET'])]
    public function listActive(RequestActiveRepository $requestActiveRepository): Response
    {
        return $this->render('admin/active/index.html.twig', [
            'request_actives' => $requestActiveRepository->findAll(),
        ]);
    }

    #[Route('/dino/new', name: 'dino_new', methods: ['GET', 'POST'])]
    public function addDino(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dino = new Dino();
        $form = $this->createForm(DinoType::class, $dino);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $dino->setPicture('no pic');
            $entityManager->persist($dino);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/add_dino/add_dino.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
