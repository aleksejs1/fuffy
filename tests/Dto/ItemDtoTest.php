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
        $this->assertEquals('1', $itemDto->getMonthsInUseString());
        $this->assertEquals(1, $itemDto->getMonthsInUse());
        $this->assertEquals(23, $itemDto->getExpireAfter());
        $this->assertEquals('48.87', $itemDto->getCurrentValue());
        $this->assertNull($itemDto->getCanChange());

        CarbonImmutable::setTestNow(Carbon::createFromDate(2022, 4, 4));
        $this->assertEquals('30.00', $itemDto->getMonthPrice());
        $this->assertEquals('2', $itemDto->getMonthsInUseString());
        $this->assertEquals(2, $itemDto->getMonthsInUse());
        $this->assertEquals(22, $itemDto->getExpireAfter());
        $this->assertEquals('46.75', $itemDto->getCurrentValue());
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
        $this->assertNull($itemDto->getCanChange());

        CarbonImmutable::setTestNow(Carbon::createFromDate(2024, 8, 4));
        $this->assertEquals('2.00', $itemDto->getMonthPrice());
        $this->assertEquals('30', $itemDto->getMonthsInUseString());
        $this->assertEquals(30, $itemDto->getMonthsInUse());
        $this->assertEquals(-6, $itemDto->getExpireAfter());
        $this->assertEquals('0', $itemDto->getCurrentValue());
        $this->assertEquals('60', $itemDto->getCanChange());
    }
}
