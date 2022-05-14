<?php

namespace App\Entity;

use App\Repository\UserReservationLinkRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserReservationLinkRepository::class)
 */
class UserReservationLink
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userReservationLinks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity=Reservations::class, inversedBy="userReservationLinks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Reservations;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getReservations(): ?Reservations
    {
        return $this->Reservations;
    }

    public function setReservations(?Reservations $Reservations): self
    {
        $this->Reservations = $Reservations;

        return $this;
    }
}
