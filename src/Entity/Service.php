<?php

namespace App\Entity;

use App\Service\Constant;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: "App\Repository\ServiceRepository")]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Veuillez renseigner le nom du service")]
    /**
     * @Assert\Length(
     *     min = 2,
     *    max = 255,
     *    minMessage = "Le nom du service doit contenir au moins {{ limit }} caractères",
     *   maxMessage = "Le nom du service doit contenir au maximum {{ limit }} caractères"
     * )
     * @Assert\Regex(
     *     pattern="/[^\w\s]/",
     *     match=false,
     *     message="Le nom du service ne peut pas contenir de caractères spéciaux"
     * )
     */
    private $name;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: "Veuillez renseigner la description du service")]
    /**
     * @Assert\Length(
     *     min = 2,
     *    max = 255,
     *   minMessage = "La description du service doit contenir au moins {{ limit }} caractères",
     *  maxMessage = "La description du service doit contenir au maximum {{ limit }} caractères"
     * )
     */
    private $description;

    #[Gedmo\Slug(fields: ['name'])]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $slug;

    #[ORM\ManyToOne(targetEntity: Fixer::class, inversedBy: 'services')]
    #[ORM\JoinColumn(nullable: false)]
    private $fixer;

    #[ORM\ManyToOne(targetEntity: Dino::class, inversedBy: 'services')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: "Veuillez choisir un dino")]
    private $dino;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'services')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: "Veuillez choisir une catégorie")]
    private $category;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: ServiceStep::class)]
    private $steps;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: Review::class)]
    private $reviews;

    #[ORM\Column(type: 'float')]
    #[Assert\NotNull(message: "Veuillez renseigner le prix du service")]
    /**
     * @Assert\Range(
     *      min = 5,
     *      max = 1000000,
     *      notInRangeMessage = "Le prix doit être compris entre 5 et 1 000 000 €"
     * )
     */
    private $price;

    #[ORM\Column(type: 'float')]
    private $rating;

    #[ORM\Column(type: 'smallint', options: ['default' => Constant::STATUS_DEFAULT])]
    private $status = Constant::STATUS_DEFAULT;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Veuillez télécharger l'image du service")]
    private $picture;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime')]
    private $created_at;

    #[Gedmo\Timestampable(on: 'update')]
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

    public function getSlug(): ?string
    {
        return $this->slug;
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
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
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
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

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setService($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getService() === $this) {
                $review->setService(null);
            }
        }

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture): self
    {
        $this->picture = $picture;

        return $this;
    }
}
