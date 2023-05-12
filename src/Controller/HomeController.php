<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/medcin', name: 'app_medcin')]
    public function goMedcin(): Response
    {
        return $this->render("Medcin.html.twig");
    }
    #[Route('/fog', name: 'app_fog')]
    public function goFOG(): Response
    {
        return $this->render('FOG.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/medicament', name: 'app_medicament')]
    public function goMedicament(): Response
    {
        return $this->render('Medicament.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    
        #[Route('/admin', name: 'app_admin')]
        public function admin(): Response
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
