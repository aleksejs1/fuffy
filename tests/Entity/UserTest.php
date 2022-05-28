<?php

namespace App\Tests\Entity;

use App\Entity\Item;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @covers \App\Entity\User::addItem
     * @covers \App\Entity\User::removeItem
     */
    public function testManipulationWithItems(): void
    {
        $user = new User();

        $this->assertCount(0, $user->getItems());
        $item1 = new Item($user);
        $this->assertCount(1, $user->getItems());
        $this->assertEquals($user, $item1->getOwner());
        $user->addItem($item1);
        $this->assertCount(1, $user->getItems());

        $item2 = new Item($user);
        $this->assertCount(2, $user->getItems());

        $this->expectException(\InvalidArgumentException::class);
        $user->removeItem($item2);
    }
}
