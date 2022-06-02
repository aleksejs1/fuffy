<?php

namespace App\Tests\Dto;

use App\Dto\ItemDto;
use App\Entity\Item;
use App\Entity\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class ItemDtoTest extends TestCase
{
    /**
     * @covers \App\Dto\ItemDto::getMonthsInUse
     * @covers \App\Dto\ItemDto::getMonthsInUseString
     * @covers \App\Dto\ItemDto::getPlanToUseInMonths
     */
    public function testMonthsInUse(): void
    {
        $owner = new User();
        $item = new Item(owner: $owner, buyDate: null);
        $itemDto = new ItemDto($item);
        $this->assertNull($itemDto->getPlanToUseInMonths());
        $this->assertNull($itemDto->getMonthsInUse());
        $this->assertNull($itemDto->getMonthsInUseString());

        $buyDate = (new \DateTime())->sub(new \DateInterval('P6M'));
        $item2 = new Item(owner: $owner, buyDate: $buyDate);
        $itemDto2 = new ItemDto($item2);
        $this->assertEquals(6, $itemDto2->getMonthsInUse());
        $this->assertEquals('6', $itemDto2->getMonthsInUseString());
    }

    /**
     * @covers \App\Dto\ItemDto::getMonthPrice
     */
    public function testMonthPrice(): void
    {
        $owner = new User();
        $item = new Item(owner: $owner, buyDate: null);
        $itemDto = new ItemDto($item);
        $this->assertNull($itemDto->getMonthPrice());

        $buyDate = (new \DateTime())->sub(new \DateInterval('P6M'));
        $item2 = new Item(owner: $owner, price: '60', buyDate: $buyDate);
        $itemDto2 = new ItemDto($item2);
        $this->assertEquals('10.00', $itemDto2->getMonthPrice());

        $item3 = new Item(owner: $owner, price: null, buyDate: $buyDate);
        $itemDto3 = new ItemDto($item3);
        $this->assertNull($itemDto3->getMonthPrice());
    }

    /**
     * @covers \App\Dto\ItemDto::getPlanMonthValue
     * @covers \App\Dto\ItemDto::getPlanToUseInMonths
     */
    public function testPlanMonthValue(): void
    {
        $owner = new User();
        $item = new Item(owner: $owner, price: '60', planToUseInMonths: 24);
        $itemDto = new ItemDto($item);
        $this->assertEquals('2.50', $itemDto->getPlanMonthValue());

        $itemWithoutPrice = new Item(owner: $owner, price: null, planToUseInMonths: 24);
        $itemWithoutPriceDto = new ItemDto($itemWithoutPrice);
        $this->assertNull($itemWithoutPriceDto->getPlanMonthValue());

        $itemWithoutPlanToUseInMonths = new Item(owner: $owner, price: '60');
        $itemWithoutPlanToUseInMonthsDto = new ItemDto($itemWithoutPlanToUseInMonths);
        $this->assertNull($itemWithoutPlanToUseInMonthsDto->getPlanToUseInMonths());
        $this->assertNull($itemWithoutPlanToUseInMonthsDto->getPlanMonthValue());

        $itemZeroPlanToUseInMonths = new Item(owner: $owner, price: '60', planToUseInMonths: 0);
        $itemZeroPlanToUseInMonthsDto = new ItemDto($itemZeroPlanToUseInMonths);
        $this->assertNull($itemZeroPlanToUseInMonthsDto->getPlanMonthValue());

        $this->expectException(\InvalidArgumentException::class);
        $itemNegativePlanToUseInMonths = new Item(owner: $owner, price: '60', planToUseInMonths: -12);
    }

    /**
     * @covers \App\Dto\ItemDto::getMonthPrice
     * @covers \App\Dto\ItemDto::getMonthsInUseString
     * @covers \App\Dto\ItemDto::getMonthsInUse
     * @covers \App\Dto\ItemDto::getExpireAfter
     * @covers \App\Dto\ItemDto::getCurrentValue
     * @covers \App\Dto\ItemDto::getCanChange
     */
    public function testFebruary(): void
    {
        $owner = new User();
        CarbonImmutable::setTestNow(Carbon::createFromDate(2022, 3, 4));
        $buyDate = Carbon::createFromDate(2022, 2, 4);
        $item = new Item(owner: $owner, price: '60', buyDate: $buyDate, planToUseInMonths: 24);
        $itemDto = new ItemDto($item);
        $this->assertEquals('60.00', $itemDto->getMonthPrice());
        $this->assertEquals('2.50', $itemDto->getPlanMonthValue());
        $this->assertEquals('1', $itemDto->getMonthsInUseString());
        $this->assertEquals(1, $itemDto->getMonthsInUse());
        $this->assertEquals(23, $itemDto->getExpireAfter());
        $this->assertEquals('48.87', $itemDto->getCurrentValue());
        $this->assertEquals('0.1', $itemDto->getTotalYears());
        $this->assertEquals('-1.9', $itemDto->getExtraYears());
        $this->assertNull($itemDto->getCanChange());

        CarbonImmutable::setTestNow(Carbon::createFromDate(2022, 4, 4));
        $this->assertEquals('30.00', $itemDto->getMonthPrice());
        $this->assertEquals('2', $itemDto->getMonthsInUseString());
        $this->assertEquals(2, $itemDto->getMonthsInUse());
        $this->assertEquals(22, $itemDto->getExpireAfter());
        $this->assertEquals('46.75', $itemDto->getCurrentValue());
        $this->assertEquals('0.2', $itemDto->getTotalYears());
        $this->assertEquals('-1.8', $itemDto->getExtraYears());
        $this->assertNull($itemDto->getCanChange());

        CarbonImmutable::setTestNow(Carbon::createFromDate(2022, 3, 19));
        $this->assertEquals('40.00', $itemDto->getMonthPrice());
        $this->assertEquals('1.5', $itemDto->getMonthsInUseString());
        $this->assertEquals(1, $itemDto->getMonthsInUse());
        $this->assertEquals(23, $itemDto->getExpireAfter());
        $this->assertEquals('47.81', $itemDto->getCurrentValue());
        $this->assertNull($itemDto->getCanChange());

        CarbonImmutable::setTestNow(Carbon::createFromDate(2022, 2, 19));
        $this->assertEquals('120.00', $itemDto->getMonthPrice());
        $this->assertEquals('0.5', $itemDto->getMonthsInUseString());
        $this->assertEquals(1, $itemDto->getMonthsInUse());
        $this->assertEquals(23, $itemDto->getExpireAfter());
        $this->assertEquals('49.93', $itemDto->getCurrentValue());
        $this->assertNull($itemDto->getCanChange());

        CarbonImmutable::setTestNow(Carbon::createFromDate(2023, 8, 4));
        $this->assertEquals('3.33', $itemDto->getMonthPrice());
        $this->assertEquals('18', $itemDto->getMonthsInUseString());
        $this->assertEquals(18, $itemDto->getMonthsInUse());
        $this->assertEquals(6, $itemDto->getExpireAfter());
        $this->assertEquals('12.75', $itemDto->getCurrentValue());
        $this->assertEquals('1.5', $itemDto->getTotalYears());
        $this->assertEquals('-0.5', $itemDto->getExtraYears());
        $this->assertNull($itemDto->getCanChange());

        CarbonImmutable::setTestNow(Carbon::createFromDate(2024, 8, 4));
        $this->assertEquals('2.00', $itemDto->getMonthPrice());
        $this->assertEquals('30', $itemDto->getMonthsInUseString());
        $this->assertEquals(30, $itemDto->getMonthsInUse());
        $this->assertEquals(-6, $itemDto->getExpireAfter());
        $this->assertEquals('0', $itemDto->getCurrentValue());
        $this->assertEquals('2.5', $itemDto->getTotalYears());
        $this->assertEquals('0.5', $itemDto->getExtraYears());
        $this->assertEquals('60', $itemDto->getCanChange());
    }
}
