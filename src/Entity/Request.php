<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: "App\Repository\RequestRepository")]
class Request
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 20)]
    private $reference;

    #[ORM\Column(type: 'string', length: 255)]
    private $subject;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\ManyToOne(targetEntity: Service::class)]
    private $service;

    #[ORM\Column(type: 'smallint')]
    private $status;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'requests')]
    #[ORM\JoinColumn(nullable: false)]
    private $customer;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    private $category;

    #[ORM\ManyToOne(targetEntity: Dino::class)]
    private $dino;

    #[ORM\Column(type: 'datetime')]
    private $expected_at;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime')]
    private $created_at;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(type: 'datetime')]
    private $updated_at;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

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

    public function getExpectedAt(): ?\DateTimeInterface
    {
        return $this->expected_at;
    }

    public function setExpectedAt(\DateTimeInterface $expected_at): self
    {
        $this->expected_at = $expected_at;

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

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): self
    {
        $this->customer = $customer;

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

    public function getDino(): ?Dino
    {
        return $this->dino;
    }

    public function setDino(?Dino $dino): self
    {
        $this->dino = $dino;

        return $this;
    }
}
