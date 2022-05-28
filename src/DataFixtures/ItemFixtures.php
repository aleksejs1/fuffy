<?php

namespace App\DataFixtures;

use App\Entity\Item;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ItemFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $testUser = $this->getReference(UserFixtures::TEST_USER);
        $anotherTestUser = $this->getReference(UserFixtures::ANOTHER_TEST_USER);
        if (!$testUser instanceof User || !$anotherTestUser instanceof User) {
            throw new \Exception('User expected');
        }
        $item1 = new Item($testUser);
        $manager->persist($item1);
        $item2 = new Item($testUser);
        $manager->persist($item2);

        $itemOfAnotherUser = new Item($anotherTestUser);
        $manager->persist($itemOfAnotherUser);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
