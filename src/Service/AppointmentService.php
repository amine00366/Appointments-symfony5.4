<?php

namespace App\Service;

use App\Entity\Appointment;
use App\Entity\Doctor;
use DateTime;

class AppointmentService
{
    private $entityManager;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function checkAppointmentAvailability(DateTime $appointmentDate, int $doctor): bool
    {
        $appointments = $this->entityManager->getRepository(Appointment::class)->findBy(['appointmentDate' => $appointmentDate, 'doctor' => $doctor]);
        
        return count($appointments) === 0;
    }
}
