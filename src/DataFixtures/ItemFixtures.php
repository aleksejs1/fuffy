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
        $buyDate = (new \DateTime())->sub(new \DateInterval('P6M'));
        $item1 = new Item(
            owner: $testUser,
            name: 'Notebook',
            model: 'Acer Aspire E5-573G',
            price: '614.02',
            buyDate: $buyDate,
            planToUseInMonths: 60
        );
        $manager->persist($item1);
        $anotherBuyDate = (new \DateTime())->sub(new \DateInterval('P75D'));
        $item2 = new Item(
            owner: $testUser,
            name: 'Phone',
            model: 'Samsung Galaxy S10',
            price: '529',
            buyDate: $anotherBuyDate,
            planToUseInMonths: 24
        );
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
