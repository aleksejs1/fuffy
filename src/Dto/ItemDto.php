<?php

namespace App\Dto;

use App\Entity\Item;
use App\Entity\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
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
        $buyDate = $this->getBuyDate();
        if (null === $buyDate) {
            return null;
        }

        return Carbon::instance($buyDate)->diffInMonths(CarbonImmutable::now());
    }

    public function getMonthsInUseString(): ?string
    {
        $buyDate = $this->getBuyDate();
        if (null === $buyDate) {
            return null;
        }

        return (string) round(Carbon::instance($buyDate)->floatDiffInMonths(CarbonImmutable::now()), 1);
    }

    public function getMonthPrice(): ?string
    {
        $price = $this->getPrice();
        if (!is_numeric($price)) {
            return null;
        }

        $monthsInUse = $this->getMonthsInUseString();
        if (!is_numeric($monthsInUse) || 0.0 === ((float) $monthsInUse)) {
            return null;
        }

        return bcdiv($price, $monthsInUse, 2);
    }

    public function getExpireAfter(): ?int
    {
        $planToUseInMonths = $this->getPlanToUseInMonths();
        if (null === $planToUseInMonths) {
            return null;
        }

        $monthsInUse = $this->getMonthsInUse();
        if (null === $monthsInUse) {
            return null;
        }

        return $planToUseInMonths - $monthsInUse;
    }

    public function getPlanMonthValue(): ?string
    {
        $price = $this->getPrice();
        if (!is_numeric($price)) {
            return null;
        }

        $planToUseInMonths = $this->getPlanToUseInMonths();
        if (null === $planToUseInMonths || 0 === $planToUseInMonths) {
            return null;
        }

        return bcdiv($price, (string) $planToUseInMonths, 2);
    }

    public function getCurrentValue(): ?string
    {
        $planToUseInMonths = $this->getPlanToUseInMonths();
        if (null === $planToUseInMonths || 0 === $planToUseInMonths) {
            return null;
        }

        $price = $this->getPrice();
        if (!is_numeric($price) || 0.0 === ((float) $price)) {
            return null;
        }

        $monthsInUse = $this->getMonthsInUseString();
        if (!is_numeric($monthsInUse)) {
            return null;
        }

        $secondHandTotalPrice = bcmul($price, '0.85', 5);
        $monthPrice = bcdiv($secondHandTotalPrice, (string) $planToUseInMonths, 5);
        $usagePrice = bcmul($monthPrice, $monthsInUse, 5);
        $currentValue = bcsub($secondHandTotalPrice, $usagePrice, 2);

        return '-' === $currentValue[0] ? '0' : $currentValue;
    }

    public function getCanChange(): ?string
    {
        $price = $this->getPrice();
        if (null === $price) {
            return null;
        }

        $planToUseInMonths = $this->getPlanToUseInMonths();
        if (null === $planToUseInMonths) {
            return null;
        }

        $monthsInUse = $this->getMonthsInUse();
        if (null === $monthsInUse) {
            return null;
        }

        return $this->planToUseInMonths <= $this->getMonthsInUse() ? $price : null;
    }

    public function getTotalYears(): ?string
    {
        $yearsInUse = $this->getYearsInUse();
        if (null === $yearsInUse) {
            return null;
        }

        return (string) round($yearsInUse, 1);
    }

    public function getYearsInUse(): ?float
    {
        $buyDate = $this->getBuyDate();
        if (null === $buyDate) {
            return null;
        }

        return Carbon::instance($buyDate)->floatDiffInYears(CarbonImmutable::now());
    }

    public function getExtraYears(): ?string
    {
        $monthsInUse = $this->getMonthsInUse();
        if (null === $monthsInUse) {
            return null;
        }

        $planToUseInMonths = $this->getPlanToUseInMonths();
        if (null === $planToUseInMonths) {
            return null;
        }

        return (string) round(($monthsInUse - $planToUseInMonths) / 12, 1);
    }
}
