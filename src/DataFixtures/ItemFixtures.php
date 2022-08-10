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

        $itemArray = [
//            ['Notebook', 'Acer Aspire E5-573G', '614.02', 'P6M', 60],
//            ['Phone', 'Samsung Galaxy S10', '529', 'P75D', 24],
            ['Стиралка','Electrolux EWF 1274 BW','276','07.10.2015',60, null],
            ['Холодильник','Samsung RB29FERNDSS/EF','411','28.09.2015',60, null],
            ['Ноут1','Acer Aspire E5-573G','614,02','23.12.2015',36, null],
            ['Телефон1','JUST5 Blaster 2','149','31.10.2015',24, '09.07.2017'],
            ['Телефон2','Samsung Galaxy S2','537','01.08.2012',48, '11.05.2016'],
            ['Телефон2','Samsung A3 2016','258','11.05.2016',24, '18.07.2017'],
            ['Мультиварка','Redmond RMC-M4502','139','17.12.2014',60, null],
            ['Телик','Sharp Lc-32LD135V','276,54','05.10.2013',60, null],
            ['Микроволновка','Samsung','109,9','29.03.2014',60, null],
            ['Чайник','Electrolux','59,99','29.03.2014',60, '15.05.2018'],
            ['Блендер','Miami Peach AW15','27,99','21.11.2015',60, null],
            ['Диван','','600','01.01.2013',60, null],
            ['Кровать','RUTI','258,98','16.01.2016',60, null],
            ['Матрас','Dormeo','530,31','16.01.2016',120, null],
            ['Велосипед1','Kenzel','150','11.05.2011',120, null],
            ['Велосипед2','Hasa bike','460','16.05.2007',120, null],
            ['Колонка','Panasonic SC-NA 10','78','06.08.2014',24, null],
            ['Уницикл','fun','89,99','12.05.2014',60, null],
            ['Стол на балкон','','49,96','10.04.2016',60, null],
            ['2 стула','','29,99','10.04.2016',60, null],
            ['Стол белый','','89,99','14.10.2015',60, null],
            ['Камера','Acme VR02','119','05.05.2015',24, null],
            ['Пылесос','','50','01.11.2013',60, '25.02.2017'],
            ['Фотик','Nikon D3100','323','07.11.2013',60, null],
            ['Электровел','Prophete','981','23.03.2018',60, null],
            ['Телефон','Samsung Galaxy S8','800','18.07.2017',36, null],
            ['Ноут2','NH.GM4EL.012','1334','17.02.2017',60, null],
            ['Телефон','Google Nexus 5x','278,54','09.07.2017',24, null],
            ['Чайник','Sage','109,9','15.05.2018',60, null],
            ['Велик','Prophete (Сашин)','490','08.08.2018',60, null],
            ['Камера','Samsung Gear 360','69','25.12.2018',24, null],
            ['Пылесос','Elektrolux Zuoorigw+','208,99','25.02.2017',60, null],
            ['Утюг','Паро-утюг','79','14.10.2018',24, null],
            ['Монитор','Монитор','188,99','20.10.2018',60, null],
            ['Клавиатура','Mechanical','139,99','20.10.2018',60, null],
            ['Зубная щётка','Зубная щётка','186,98','10.05.2017',24, null],
            ['Смарт часы','Garmin Vivoactive HR','239,99','23.09.2016',60, '23.09.2021'],
            ['Дрон','Drone','116,4','19.05.2017',24, null],
            ['Пылесос','Roborock gen2','424','07.01.2019',24, null],
            ['Телик','LG OLED65CX3LA','1632','23.09.2020',60, null],
            ['Телефон','Samsung Galaxy S10','529','26.11.2020',36, null],
            ['Роутер','Huawey Ax3','70','27.11.2020',60, null],
            ['Увлажнитель','Electrolux Sense','82','15.10.2021',60, null],
            ['Лампа','Patrick','15,99','17.10.2021',60, null],
            ['Ratini','120kg','72,09','08.02.2020',60, null],
            ['Сэлфи палка','EMM115','12,99','22.08.2019',60, null],
            ['FM-модулятор','F330 Acme','19,99','22.08.2019',60, null],
            ['Видео-адаптер','UVG-002','16,95','24.09.2021',60, null],
            ['Сушилка','Из Lidl','29,99','10.11.2021',60, null],
            ['Мышка','Logitech MX Master 3','86','15.12.2021',60, null],
            ['Часы','Garmin Fenix 7 Saprire','899,99','20.01.2022',60, null],
            ['Телефон','Samsung Galaxy S22','751,24','09.02.2022',36, null],
            ['Блендер','Bosch ErgoMixx Style','95,19','22.12.2021',60, null],

        ];

        foreach ($itemArray as $rawItem) {
//            $buyDate = (new \DateTime())->sub(new \DateInterval($rawItem[3]));
            $buyDate = new \DateTime($rawItem[3]);
            $item = new Item(
                owner: $testUser,
                name: $rawItem[0],
                model: $rawItem[1],
                price: $rawItem[2],
                buyDate: $buyDate,
                planToUseInMonths: $rawItem[4]
            );
            if ($rawItem[5]) {
                $item->setEndDate(new \DateTime($rawItem[5]));
            }

            $manager->persist($item);
        }
//        $buyDate = (new \DateTime())->sub(new \DateInterval('P6M'));
//        $item1 = new Item(
//            owner: $testUser,
//            name: 'Notebook',
//            model: 'Acer Aspire E5-573G',
//            price: '614.02',
//            buyDate: $buyDate,
//            planToUseInMonths: 60
//        );
//        $manager->persist($item1);
//        $anotherBuyDate = (new \DateTime())->sub(new \DateInterval('P75D'));
//        $item2 = new Item(
//            owner: $testUser,
//            name: 'Phone',
//            model: 'Samsung Galaxy S10',
//            price: '529',
//            buyDate: $anotherBuyDate,
//            planToUseInMonths: 24
//        );
//        $manager->persist($item2);

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
