<?php

use App\Entity\Appointment;
use App\Form\AppointmentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentController extends AbstractController
{
   

    /**
     * @Route("/appointment/success", name="appointment_success")
     */
    public function success()
    {
        return $this->render('appointment/success.html.twig');
    }
}
