<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdminRepository;

class UserListController extends AbstractController
{
    #[Route('admin', name: 'user_list')]
    public function index(): Response
    {
        $adminRepository = $this->getDoctrine()->getRepository(Admin::class);
        $allFixer = $adminRepository->findAllFixer();
        $allCustomer = $adminRepository->findAllCustomer();
        $allUser = array_merge($allFixer, $allCustomer);



        return $this->render('admin/user_list/admin.html.twig', [
            'controller_name' => 'UserListController',
            'allUser' => $allUser,
        ]);
    }
}
