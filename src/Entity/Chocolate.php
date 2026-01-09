<?php

namespace App\Entity;

use App\Repository\ChocolateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChocolateRepository::class)]
class Chocolate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $chocolate = null;

    #[ORM\Column(type: "float")]
    private ?float $price = null;

    #[ORM\Column]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChocolate(): ?string
    {
        return $this->chocolate;
    }

    public function setChocolate(string $chocolate): self
    {
        $this->chocolate = $chocolate;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;
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
}
