<?php

namespace App\Tests\Dto;

use App\Dto\ItemDto;
use App\Entity\Item;
use App\Entity\User;
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
}
