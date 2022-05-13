<?php

namespace App\Entity;

use App\Repository\HallRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HallRepository::class)
 */
class Hall
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="text")
     */
    private $About;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfSeats;

    /**
     * @ORM\OneToMany(targetEntity=Reservations::class, mappedBy="Hall")
     */
    private $Hall;

    public function __construct()
    {
        $this->Hall = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->About;
    }

    public function setAbout(string $About): self
    {
        $this->About = $About;

        return $this;
    }

    public function getNumberOfSeats(): ?int
    {
        return $this->numberOfSeats;
    }

    public function setNumberOfSeats(int $numberOfSeats): self
    {
        $this->numberOfSeats = $numberOfSeats;

        return $this;
    }

    /**
     * @return Collection<int, Reservations>
     */
    public function getHall(): Collection
    {
        return $this->Hall;
    }

    public function addHall(Reservations $Hall): self
    {
        if (!$this->Hall->contains($Hall)) {
            $this->Hall[] = $Hall;
            $Hall->setHall($this);
        }

        return $this;
    }

    public function removeHall(Reservations $Hall): self
    {
        if ($this->DateTimeFrom->removeElement($Hall)) {
            // set the owning side to null (unless already changed)
            if ($Hall->getHall() === $this) {
                $Hall->setHall(null);
            }
        }

        return $this;
    }
}
