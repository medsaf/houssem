<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UnavailablePeriodRepository")
 */
class UnavailablePeriod
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $beginning;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $end_date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room", inversedBy="unavailablePeriods")
     */
    private $relation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBeginning(): ?\DateTimeInterface
    {
        return $this->beginning;
    }

    public function setBeginning(\DateTimeInterface $beginning): self
    {
        $this->beginning = $beginning;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getRelation(): ?Room
    {
        return $this->relation;
    }

    public function setRelation(?Room $relation): self
    {
        $this->relation = $relation;

        return $this;
    }
    
    public function __toString() {
        return (string) $this->getId();
    }
}
