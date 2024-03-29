<?php

namespace App\Entity;

use App\Service\Constant;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: "App\Repository\ReviewRepository")]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Customer::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $customer;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: "Veuillez entrer une note")]
    /**
     * @Assert\Range(
     *     min = 1,
     *     max = 5,
     *     minMessage = "La note doit être au moins {{ limit }}",
     *     maxMessage = "La note doit être au maximum {{ limit }}"
     * )
     */
    private $rate;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Veuillez entrer un commentaire")]
    /**
     * @Assert\Length(
     *     min = 2,
     *     max = 255,
     *     minMessage = "Le commentaire doit contenir au moins {{ limit }} caractères",
     *     maxMessage = "Le commentaire doit contenir au maximum {{ limit }} caractères"
     * )
     */
    private $message;

    #[ORM\ManyToOne(targetEntity: Fixer::class, inversedBy: 'reviews')]
    private $fixer;

    #[ORM\ManyToOne(targetEntity: Service::class, inversedBy: 'reviews')]
    private $service;

    #[ORM\Column(type: 'smallint', options: ['default' => Constant::STATUS_DEFAULT])]
    private $status = Constant::STATUS_DEFAULT;

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

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

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

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }
}
