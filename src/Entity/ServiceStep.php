<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: "App\Repository\ServiceStepRepository")]
class ServiceStep
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    
    private $step;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank(message: "Veuillez renseigner le nom de l'étape")]
    /**
     * @Assert\Length(
     *     min = 2,
     *    max = 100,
     *    minMessage = "Le nom de l'étape doit contenir au moins {{ limit }} caractères",
     *   maxMessage = "Le nom de l'étape catégorie doit contenir au maximum {{ limit }} caractères"
     * )
     * @Assert\Regex(
     *     pattern="/[^\w\s]/",
     *     match=false,
     *     message="Le nomde l'étape ne peut pas contenir de caractères spéciaux"
     * )
     */
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Veuillez renseigner la description de l'étape")]
    /**
     * @Assert\Length(
     *     min = 2,
     *    max = 255,
     *   minMessage = "La description de l'étape doit contenir au moins {{ limit }} caractères",
     *  maxMessage = "La description de l'étape doit contenir au maximum {{ limit }} caractères"
     * )
     */
    private $description;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private $notify = false;

    #[ORM\ManyToOne(targetEntity: Service::class, inversedBy: 'steps')]
    private $service;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStep(): ?int
    {
        return $this->step;
    }

    public function setStep(int $step): self
    {
        $this->step = $step;

        return $this;
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

    public function getNotify(): bool
    {
        return $this->notify;
    }

    public function setNotify(bool $notify): self
    {
        $this->notify = $notify;

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
