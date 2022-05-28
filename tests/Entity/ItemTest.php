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
}
