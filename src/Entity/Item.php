<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $model = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $price = '0.00';

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTimeInterface $buyDate = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $planToUseInMonths = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private User $owner;

    public function __construct(User $owner)
    {
        $this->owner = $owner;
        $owner->addItem($this);
    }

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

    public function getBuyDate(): ?DateTimeInterface
    {
        return $this->buyDate;
    }

    public function setBuyDate(?DateTimeInterface $buyDate): self
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

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): self
    {
        if ($this->getOwner() === $owner) {
            return $this;
        }

        $oldOwner = $this->getOwner();

        $this->owner = $owner;
        if (!$this->getOwner()->getItems()->contains($this)) {
            $this->getOwner()->addItem($this);
        }

        if ($oldOwner->getItems()->contains($this)) {
            $oldOwner->removeItem($this);
        }

        return $this;
    }
}
