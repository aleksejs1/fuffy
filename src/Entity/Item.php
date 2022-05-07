<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $model;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private $price;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $buyDate;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $planToUseInMonths;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private $owner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBuyDate(): ?\DateTimeInterface
    {
        return $this->buyDate;
    }

    public function setBuyDate(?\DateTimeInterface $buyDate): self
    {
        $this->buyDate = $buyDate;

        return $this;
    }

    public function getPlanToUseInMonths(): ?int
    {
        return $this->planToUseInMonths;
    }

    public function setPlanToUseInMonths(?int $planToUseInMonths): self
    {
        $this->planToUseInMonths = $planToUseInMonths;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
