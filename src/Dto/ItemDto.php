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

        $diff = (new \DateTime())->diff($this->buyDate);

        return ((int) $diff->format('%m')) + (((int) $diff->format('%y')) * 12);
    }

    public function getMonthsInUseString(): ?string
    {
        if (!$this->buyDate) {
            return null;
        }

        $diff = (new \DateTime())->diff($this->buyDate);
        $months = (string) (((int) $diff->format('%m')) + (((int) $diff->format('%y')) * 12));

        return $months.'.'.(round((int) $diff->format('%d') / 30));
    }

    public function getMonthPrice(): ?string
    {
        $monthsInUse = $this->getMonthsInUse();
        if ($this->price && $this->buyDate && null !== $monthsInUse && $monthsInUse > 0) {
            return (string) round((float) $this->price / $monthsInUse, 2);
        }

        return null;
    }

    public function getExpireAfter(): ?int
    {
        $monthsInUse = $this->getMonthsInUse();
        if ($this->buyDate && $this->planToUseInMonths && null !== $monthsInUse) {
            return $this->planToUseInMonths - $monthsInUse;
        }

        return null;
    }

    public function getPlanMonthValue(): ?string
    {
        if ($this->price && $this->planToUseInMonths) {
            return (string) round((float) $this->price / $this->planToUseInMonths, 2);
        }

        return null;
    }

    public function getCurrentValue(): ?string
    {
        $monthsInUse = $this->getMonthsInUse();
        if (null !== $this->price
            && null !== $this->planToUseInMonths
            && null !== $this->buyDate
            && null !== $monthsInUse
        ) {
            $secondHandTotalPrice = (float) $this->price * 0.85;
            $usagePrice = $secondHandTotalPrice / $this->planToUseInMonths * $monthsInUse;

            return (string) round(max($secondHandTotalPrice - $usagePrice, 0), 2);
        }

        return null;
    }

    public function getCanChange(): ?string
    {
        if ($this->planToUseInMonths && $this->buyDate) {
            return $this->planToUseInMonths <= $this->getMonthsInUse() ? $this->getPrice() : null;
        }

        return null;
    }

    public function getTotalYears(): ?string
    {
        $monthsInUse = $this->getMonthsInUse();
        if ($monthsInUse) {
            return (string) round($monthsInUse / 12, 1);
        }

        return null;
    }

    public function getExtraYears(): ?string
    {
        $monthsInUse = $this->getMonthsInUse();
        if ($monthsInUse && $this->planToUseInMonths) {
            return (string) round(($monthsInUse - $this->planToUseInMonths) / 12, 1);
        }

        return null;
    }
}
