<?php

namespace App\Entity;

use App\Repository\ScoreTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScoreTypeRepository::class)]
class ScoreType
{
    public static $score_type_point = 'point';
    public static $score_type_classement = 'classement';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'scoreType', targetEntity: Sport::class)]
    private Collection $sports;

    #[ORM\OneToMany(mappedBy: 'scoreType', targetEntity: Score::class)]
    private Collection $scores;

    public function __construct()
    {
        $this->sports = new ArrayCollection();
        $this->scores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Sport>
     */
    public function getSports(): Collection
    {
        return $this->sports;
    }

    public function addSport(Sport $sport): static
    {
        if (!$this->sports->contains($sport)) {
            $this->sports->add($sport);
            $sport->setScoreType($this);
        }

        return $this;
    }

    public function removeSport(Sport $sport): static
    {
        if ($this->sports->removeElement($sport)) {
            // set the owning side to null (unless already changed)
            if ($sport->getScoreType() === $this) {
                $sport->setScoreType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Score>
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): static
    {
        if (!$this->scores->contains($score)) {
            $this->scores->add($score);
            $score->setScoreType($this);
        }

        return $this;
    }

    public function removeScore(Score $score): static
    {
        if ($this->scores->removeElement($score)) {
            // set the owning side to null (unless already changed)
            if ($score->getScoreType() === $this) {
                $score->setScoreType(null);
            }
        }

        return $this;
    }
}
