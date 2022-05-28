<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const TEST_USER = 'test_user';
    public const ANOTHER_TEST_USER = 'another_test_user';

    protected $referenceRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('test');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'test'));
        $manager->persist($user);

        $anotherUser = new User();
        $anotherUser->setUsername('anotherTest');
        $anotherUser->setPassword($this->passwordHasher->hashPassword($anotherUser, 'test'));
        $manager->persist($anotherUser);

        $manager->flush();

        $this->addReference(self::TEST_USER, $user);
        $this->addReference(self::ANOTHER_TEST_USER, $anotherUser);
    }
}
