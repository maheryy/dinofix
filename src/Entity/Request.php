<?php

namespace App\Entity;

use App\Service\Constant;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: "App\Repository\RequestRepository")]
class Request
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 8, unique: true)]
    private $reference;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Veuillez renseigner le sujet de la demande")]
    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Le sujet de la demande doit contenir au moins {{ limit }} caractères",
     *      maxMessage = "Le sujet de la demande doit contenir au maximum {{ limit }} caractères"
     * )
     * @Assert\Regex(
     *     pattern="/[^\w\s]/",
     *     match=false,
     *     message="Le sujet de la demande ne peut pas contenir de caractères spéciaux"
     * )
     */
    private $subject;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: "Veuillez renseigner la description du service")]
    /**
     * @Assert\Length(
     *    min = 2,
     *    max = 255,
     *    minMessage = "La description de la demande doit contenir au moins {{ limit }} caractères",
     *    maxMessage = "La description de la demande doit contenir au maximum {{ limit }} caractères"
     * )
     */
    private $description;

    #[Gedmo\Slug(fields: ['reference'])]
    #[ORM\Column(type: 'string', length: 8, unique: true)]
    private $slug;

    #[ORM\ManyToOne(targetEntity: Service::class)]
    private $service;

    #[ORM\Column(type: 'smallint', options: ['default' => Constant::STATUS_DEFAULT])]
    private $status = Constant::STATUS_DEFAULT;

    #[ORM\Column(type: 'string', nullable: true)]
    private $payment_reference;

    #[ORM\ManyToOne(targetEntity: Customer::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $customer;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    private $category;

    #[ORM\ManyToOne(targetEntity: Dino::class)]
    private $dino;

    #[ORM\Column(type: 'datetime', nullable: true)]
    /**
    * @Assert\Range(
    *      min = "now",
    *      max = "+7 days",
    *      notInRangeMessage = "La date doit être entre aujourd'hui et le {{ max }}"
    * )
    */
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

    public function getSlug(): ?string
    {
        return $this->slug;
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

    public function getPaymentReference(): ?string
    {
        return $this->payment_reference;
    }

    public function setPaymentReference(?string $payment_reference): self
    {
        $this->payment_reference = $payment_reference;

        return $this;
    }

    public function getExpectedAt(): ?\DateTimeInterface
    {
        return $this->expected_at;
    }

    public function setExpectedAt(?\DateTimeInterface $expected_at): self
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

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
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
