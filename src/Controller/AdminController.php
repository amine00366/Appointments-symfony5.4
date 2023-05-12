<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="app_admin", methods={"GET"})
     */
    
    public function index(): Response
    {
        return $this->render('admin/test.html.twig');
    }
    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        return $this->render('admin/profile.html.twig');
    }
    #[Route('/dashboard', name: 'app_dashboard')]
    public function dash(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }
    
       
}