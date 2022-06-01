<?php

namespace App\Tests\Entity;

use App\Entity\Item;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    /**
     * @covers \App\Entity\Item::setOwner
     */
    public function testManipulationWithOwner(): void
    {
        $user1 = new User();
        $item = new Item($user1);

        $this->assertEquals($user1, $item->getOwner());
        $this->assertContains($item, $user1->getItems());

        $item->setOwner($user1);
        $this->assertEquals($user1, $item->getOwner());

        $user2 = new User();
        $item->setOwner($user2);
        $this->assertEquals($user2, $item->getOwner());
        $this->assertContains($item, $user2->getItems());
        $this->assertNotContains($item, $user1->getItems());
    }

    /**
     * @covers \App\Entity\Item::setPrice
     * @covers \App\Entity\Item::getPrice
     */
    public function testPrice(): void
    {
        $user = new User();
        $item = new Item($user);

        $this->assertEquals('0.00', $item->getPrice());
        $price = '1.01';
        $item->setPrice($price);
        $this->assertEquals($price, $item->getPrice());
        $priceWithComa = '1,01';
        $item->setPrice($priceWithComa);
        $this->assertEquals($price, $item->getPrice());
        $negativePrice = ' -1.01';
        $this->expectException(\InvalidArgumentException::class);
        $item->setPrice($negativePrice);
    }

    /**
     * @covers \App\Entity\Item::setPrice
     */
    public function testNonNumericPrice(): void
    {
        $user = new User();
        $item = new Item($user);

        $this->expectException(\InvalidArgumentException::class);
        $item->setPrice('Eleven');
    }

    /**
     * @covers \App\Entity\Item::setPrice
     */
    public function testNonNumericPriceInConstructor(): void
    {
        $user = new User();
        $this->expectException(\InvalidArgumentException::class);
        $item = new Item(owner: $user, price: 'Eleven');
    }

    /**
     * @covers \App\Entity\Item::setPlanToUseInMonths
     * @covers \App\Entity\Item::getPlanToUseInMonths
     */
    public function testSetPlanToUseInMonths(): void
    {
        $user = new User();
        $item = new Item($user);

        $item->setPlanToUseInMonths(1);
        $this->assertEquals(1, $item->getPlanToUseInMonths());
        $item->setPlanToUseInMonths(0);
        $this->assertEquals(0, $item->getPlanToUseInMonths());
        $item->setPlanToUseInMonths(null);
        $this->assertNull($item->getPlanToUseInMonths());

        $this->expectException(\InvalidArgumentException::class);
        $item->setPlanToUseInMonths(-1);
    }
}
