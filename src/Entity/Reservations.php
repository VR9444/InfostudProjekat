<?php

namespace App\Entity;

use App\Repository\ReservationsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationsRepository::class)
 */
class Reservations
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Hall::class, inversedBy="DateTimeFrom")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Hall;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateTimeFrom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateTimeTo;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $CreatedBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHall(): ?Hall
    {
        return $this->Hall;
    }

    public function setHall(?Hall $Hall): self
    {
        $this->Hall = $Hall;

        return $this;
    }

    public function getDateTimeFrom(): ?\DateTimeInterface
    {
        return $this->DateTimeFrom;
    }

    public function setDateTimeFrom(\DateTimeInterface $DateTimeFrom): self
    {
        $this->DateTimeFrom = $DateTimeFrom;

        return $this;
    }

    public function getDateTimeTo(): ?\DateTimeInterface
    {
        return $this->DateTimeTo;
    }

    public function setDateTimeTo(\DateTimeInterface $DateTimeTo): self
    {
        $this->DateTimeTo = $DateTimeTo;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->CreatedBy;
    }

    public function setCreatedBy(?User $CreatedBy): self
    {
        $this->CreatedBy = $CreatedBy;

        return $this;
    }
}
