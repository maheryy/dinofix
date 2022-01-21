<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\ServiceRepository")]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 15)]
    private $name;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\ManyToOne(targetEntity: Fixer::class, inversedBy: 'services')]
    #[ORM\JoinColumn(nullable: false)]
    private $fixer;

    #[ORM\ManyToOne(targetEntity: Dino::class, inversedBy: 'services')]
    private $dino;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'services')]
    private $category;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: ServiceStep::class)]
    private $steps;

    #[ORM\OneToMany(mappedBy: 'fixer', targetEntity: Fixer::class)]
    private $reviews;

    #[ORM\Column(type: 'smallint')]
    private $status;

    #[ORM\Column(type: 'datetime')]
    private $created_at;

    #[ORM\Column(type: 'datetime')]
    private $updated_at;

    public function __construct()
    {
        $this->steps = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDino(): ?Dino
    {
        return $this->dino;
    }

    public function setDino(?Dino $dino): self
    {
        $this->dino = $dino;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|ServiceStep[]
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(ServiceStep $step): self
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->setService($this);
        }

        return $this;
    }

    public function removeStep(ServiceStep $step): self
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getService() === $this) {
                $step->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Fixer[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Fixer $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setFixer($this);
        }

        return $this;
    }

    public function removeReview(Fixer $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getFixer() === $this) {
                $review->setFixer(null);
            }
        }

        return $this;
    }

    public function getFixer(): ?Fixer
    {
        return $this->fixer;
    }

    public function setFixer(?Fixer $fixer): self
    {
        $this->fixer = $fixer;

        return $this;
    }
}
