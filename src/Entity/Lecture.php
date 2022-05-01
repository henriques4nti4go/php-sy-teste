<?php

namespace App\Entity;

use App\Repository\LectureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LectureRepository::class)]
class Lecture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('lecture')]
    private $id;

    #[Groups('lecture')]
    #[ORM\Column(type: 'string', length: 50)]
    private $title;

    #[Groups('lecture')]
    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[Groups('lecture')]
    #[ORM\Column(type: 'date')]
    private $date;

    #[Groups('lecture')]
    #[ORM\Column(type: 'time')]
    private $start_hour;

    #[Groups('lecture')]
    #[ORM\Column(type: 'time')]
    private $final_hour;

    #[Groups('lecture')]
    #[ORM\Column(type: 'string', length: 50)]
    private $speaker;

    #[Groups('event')]
    #[ORM\ManyToOne(targetEntity: Event::class, inversedBy: 'lectures')]
    #[ORM\JoinColumn(nullable: false)]
    private $event;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStartHour(): ?\DateTimeInterface
    {
        return $this->start_hour;
    }

    public function setStartHour(\DateTimeInterface $start_hour): self
    {
        $this->start_hour = $start_hour;

        return $this;
    }

    public function getFinalHour(): ?\DateTimeInterface
    {
        return $this->final_hour;
    }

    public function setFinalHour(\DateTimeInterface $final_hour): self
    {
        $this->final_hour = $final_hour;

        return $this;
    }

    public function getSpeaker(): ?string
    {
        return $this->speaker;
    }

    public function setSpeaker(string $speaker): self
    {
        $this->speaker = $speaker;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }
}
