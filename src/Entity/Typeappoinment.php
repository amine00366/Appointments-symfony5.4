<?php

namespace App\Entity;

use App\Repository\TypeappoinmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TypeappoinmentRepository::class)]
class Typeappoinment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("Typeappoinment")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message: "il faut ajouter un nom de type!") ]
    #[Groups("Typeappoinment")]
    private ?string $nomtype = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Appointment::class )]
  
    private Collection $appointments;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message: "il faut ajouter une Description") ]
    #[Groups("Typeappoinment")]
    private ?string $description = null;

    public function __construct()
    {
        $this->appointments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomtype(): ?string
    {
        return $this->nomtype;
    }

    public function setNomtype( $nomtype): self
    {
        $this->nomtype = $nomtype;

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): self
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments->add($appointment);
            $appointment->setType($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getType() === $this) {
                $appointment->setType(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription( $description): self
    {
        $this->description = $description;

        return $this;
    }
    public function __toString()
    {
        return $this->nomtype;
    }

}
