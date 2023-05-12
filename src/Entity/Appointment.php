<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    private ?Doctor $doctor = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups("appoinment")]
    private ?\DateTimeInterface $appointmentDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups("appoinment")]
    private ?\DateTimeInterface $datefin = null;

    #[ORM\ManyToOne(inversedBy: 'appointments'  )] 
    #[Assert\NotBlank (message: "il faut ajouter un type!") ]
    private ?Typeappoinment $type = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message: "il faut ajouter une catégorie") ]
    #[Groups("appoinment")]
    private ?string $categorie = null;
    #[ORM\Column(type:"boolean", options:['default' => false])]
    #[Groups("appoinment")]
    private $approved = false;

   public function isApproved(): bool
   {
       return $this->approved;
   }

   public function setApproved(bool $approved): self
   {
       $this->approved = $approved;

       return $this;
   }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctor $doctor): self
    {
        $this->doctor = $doctor;

        return $this;
    }

    public function getAppointmentDate(): ?\DateTimeInterface
    {
        return $this->appointmentDate;
    }

    public function setAppointmentDate(\DateTimeInterface $appointmentDate): self
    {
        $this->appointmentDate = $appointmentDate;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getType(): ?Typeappoinment
    {
        return $this->type;
    }

    public function setType(?Typeappoinment $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
  

   
}
