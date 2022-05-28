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
     */
    public function testMonthsInUse(): void
    {
        $owner = new User();
        $item = new Item(owner: $owner);
        $itemDto = new ItemDto($item);
        $this->assertNull($itemDto->getPlanToUseInMonths());

        $buyDate = (new \DateTime())->sub(new \DateInterval('P6M'));
        $item2 = new Item(owner: $owner, buyDate: $buyDate);
        $itemDto2 = new ItemDto($item2);
        $this->assertEquals(6, $itemDto2->getMonthsInUse());
    }
}
