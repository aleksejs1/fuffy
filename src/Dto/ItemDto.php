<?php

namespace App\Dto;

use App\Entity\Item;
use App\Entity\User;
use DateTimeInterface;

class ItemDto
{
    private ?int $id = null;
    private User $owner;
    private ?string $name;
    private ?string $model;
    private ?string $price;
    private ?DateTimeInterface $buyDate;
    private ?int $planToUseInMonths;

    public function __construct(Item $item)
    {
        $this->id = $item->getId();
        $this->name = $item->getName();
        $this->model = $item->getModel();
        $this->price = $item->getPrice();
        $this->buyDate = $item->getBuyDate();
        $this->planToUseInMonths = $item->getPlanToUseInMonths();
        $this->owner = $item->getOwner();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function getBuyDate(): ?DateTimeInterface
    {
        return $this->buyDate;
    }

    public function getPlanToUseInMonths(): ?int
    {
        return $this->planToUseInMonths;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function getMonthsInUse(): ?int
    {
        if (!$this->buyDate) {
            return null;
        }

        return (int) (new \DateTime())->diff($this->buyDate)->format('%m');
    }
}
