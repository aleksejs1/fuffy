<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, nullable: false, options: ['unsigned' => true])]
    private ?int $id = null;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'items')]
        #[ORM\JoinColumn(nullable: false)]
        private User $owner,

        #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
        private ?string $name = null,

        #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
        private ?string $model = null,

        #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
        private ?string $price = '0.00',

        #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
        private ?DateTimeInterface $buyDate = new \DateTime(),

        #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
        private ?DateTimeInterface $endDate = null,

        #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['unsigned' => true])]
        private ?int $planToUseInMonths = null,
    ) {
        $owner->addItem($this);

        if ($planToUseInMonths!== null && $planToUseInMonths < 0) {
            throw new \InvalidArgumentException('Item::$planToUseInMonths should be positive');
        }
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
        $this->price = str_replace(',', '.', $price);

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

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getPlanToUseInMonths(): ?int
    {
        return $this->planToUseInMonths;
    }

    public function setPlanToUseInMonths(?int $planToUseInMonths): self
    {
        if ($planToUseInMonths!== null && $planToUseInMonths < 0) {
            throw new \InvalidArgumentException('Item::$planToUseInMonths should be positive');
        }

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
