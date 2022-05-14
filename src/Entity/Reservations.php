<?php

namespace App\Entity;

use App\Repository\ReservationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

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

    /**
     * @ORM\OneToMany(targetEntity=UserReservationLink::class, mappedBy="Reservations")
     */
    private $userReservationLinks;

    public function __construct()
    {
        $this->userReservationLinks = new ArrayCollection();
    }

    public function getId(): ?\DateTime
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

    public function getDateTimeFrom(): ?\DateTime
    {
        return $this->DateTimeFrom;
    }

    public function setDateTimeFrom(\DateTime $DateTimeFrom): self
    {
        $this->DateTimeFrom = $DateTimeFrom;

        return $this;
    }

    public function getDateTimeTo(): \DateTime
    {
        return $this->DateTimeTo;
    }

    public function setDateTimeTo(\DateTime $DateTimeTo): self
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

    /**
     * @return Collection<int, UserReservationLink>
     */
    public function getUserReservationLinks(): Collection
    {
        return $this->userReservationLinks;
    }

    public function addUserReservationLink(UserReservationLink $userReservationLink): self
    {
        if (!$this->userReservationLinks->contains($userReservationLink)) {
            $this->userReservationLinks[] = $userReservationLink;
            $userReservationLink->setReservations($this);
        }

        return $this;
    }

    public function removeUserReservationLink(UserReservationLink $userReservationLink): self
    {
        if ($this->userReservationLinks->removeElement($userReservationLink)) {
            // set the owning side to null (unless already changed)
            if ($userReservationLink->getReservations() === $this) {
                $userReservationLink->setReservations(null);
            }
        }

        return $this;
    }
}
