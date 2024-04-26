<?php

namespace App\Entity;

use App\Repository\ScoreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScoreRepository::class)]
class Score
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $value = null;

    #[ORM\OneToMany(mappedBy: 'score', targetEntity: EventParticipant::class)]
    private Collection $eventParticipants;

    #[ORM\ManyToOne(inversedBy: 'scores')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ScoreType $scoreType = null;

    public function __construct()
    {
        $this->eventParticipants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection<int, EventParticipant>
     */
    public function getEventParticipants(): Collection
    {
        return $this->eventParticipants;
    }

    public function addEventParticipant(EventParticipant $eventParticipant): static
    {
        if (!$this->eventParticipants->contains($eventParticipant)) {
            $this->eventParticipants->add($eventParticipant);
            $eventParticipant->setScore($this);
        }

        return $this;
    }

    public function removeEventParticipant(EventParticipant $eventParticipant): static
    {
        if ($this->eventParticipants->removeElement($eventParticipant)) {
            // set the owning side to null (unless already changed)
            if ($eventParticipant->getScore() === $this) {
                $eventParticipant->setScore(null);
            }
        }

        return $this;
    }

    public function getScoreType(): ?ScoreType
    {
        return $this->scoreType;
    }

    public function setScoreType(?ScoreType $scoreType): static
    {
        $this->scoreType = $scoreType;

        return $this;
    }
}
