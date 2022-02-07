<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: "App\Repository\CategoryRepository")]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[Gedmo\Slug(fields: ['name'])]
    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private $slug;

    #[ORM\Column(type: 'string', length: 255)]
    private $picture;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Dino::class)]
    private $dinos;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Service::class)]
    private $services;

    public function __construct()
    {
        $this->dinos = new ArrayCollection();
        $this->services = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection|Dino[]
     */
    public function getDinos(): Collection
    {
        return $this->dinos;
    }

    public function addDino(Dino $dino): self
    {
        if (!$this->dinos->contains($dino)) {
            $this->dinos[] = $dino;
            $dino->setCategory($this);
        }

        return $this;
    }

    public function removeDino(Dino $dino): self
    {
        if ($this->dinos->removeElement($dino)) {
            // set the owning side to null (unless already changed)
            if ($dino->getCategory() === $this) {
                $dino->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setCategory($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getCategory() === $this) {
                $service->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
