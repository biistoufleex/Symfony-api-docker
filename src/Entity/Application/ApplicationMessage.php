<?php

namespace App\Entity\Application;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\Application\ApplicationMessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ApplicationMessageRepository::class)]
#[ApiResource]
class ApplicationMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    private ?string $usecase = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    private ?string $uri = null;

    #[ORM\Column(length: 1500, nullable: true)]
    #[Assert\Length(max: 1500)]
    private ?string $message = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsecase(): ?string
    {
        return $this->usecase;
    }

    public function setUsecase(string $usecase): static
    {
        $this->usecase = $usecase;

        return $this;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(string $uri): static
    {
        $this->uri = $uri;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }


}
