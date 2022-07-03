<?php

namespace App\Entity;

use App\Service\Constant;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: "App\Repository\RequestActiveRepository")]
class RequestActive
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Request::class)]
    private $request;

    #[ORM\ManyToOne(targetEntity: Fixer::class)]
    private $fixer;

    #[ORM\ManyToOne(targetEntity: ServiceStep::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private $step;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $content;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

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

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setRequest(?Request $request): self
    {
        $this->request = $request;

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

    public function getStep(): ?ServiceStep
    {
        return $this->step;
    }

    public function setStep(?ServiceStep $step): self
    {
        $this->step = $step;

        return $this;
    }
}
