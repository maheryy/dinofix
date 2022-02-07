<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Repository\CustomerRepository;
use App\Repository\FixerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserListController extends AbstractController
{
    #[Route('admin', name: 'user_list')]
    public function index(FixerRepository $fixerRepository, CustomerRepository $customerRepository): Response
    {

        $customer = $customerRepository->findAllCustomer();
        $fixer = $fixerRepository->findAllFixer();
        $allUser = array_merge($fixer,$customer );

        return $this->render('admin/user_list/admin.html.twig', [
            'controller_name' => 'UserListController',
            'allUser' => $allUser,
        ]);
    }
}
