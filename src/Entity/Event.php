<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{

    #[ORM\Id]
    #[Groups('event')]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups('event')]
    #[ORM\Column(type: 'string', length: 50)]
    private $title;

    #[Groups('event')]
    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[Groups('event')]
    #[ORM\Column(type: 'datetime')]
    private $start_datetime;

    #[Groups('event')]
    #[ORM\Column(type: 'datetime')]
    private $final_datetime;


    #[Groups('event')]
    #[Assert\Choice(['Agendado', 'Acontecendo', 'Acontecendo', 'Cancelado'], message: 'Status incorreto!')]
    #[ORM\Column(type: 'string', length: 25)]
    private $status;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Lecture::class)]
    private $lectures;

    public function __construct()
    {
        $this->lectures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDatetime(): ?\DateTimeInterface
    {
        return $this->start_datetime;
    }

    public function setStartDatetime(\DateTimeInterface $start_datetime): self
    {
        $this->start_datetime = $start_datetime;

        return $this;
    }

    public function getFinalDatetime(): ?\DateTimeInterface
    {
        return $this->final_datetime;
    }

    public function setFinalDatetime(\DateTimeInterface $final_datetime): self
    {
        $this->final_datetime = $final_datetime;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Lecture>
     */
    public function getLectures(): Collection
    {
        return $this->lectures;
    }

    public function addLecture(Lecture $lecture): self
    {
        if (!$this->lectures->contains($lecture)) {
            $this->lectures[] = $lecture;
            $lecture->setEvent($this);
        }

        return $this;
    }

    public function removeLecture(Lecture $lecture): self
    {
        if ($this->lectures->removeElement($lecture)) {
            // set the owning side to null (unless already changed)
            if ($lecture->getEvent() === $this) {
                $lecture->setEvent(null);
            }
        }

        return $this;
    }
}
