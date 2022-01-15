<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    /**@var UserPasswordHasherInterface $passwordHash */
    private $userPasswordHash;

    public function __construct(UserPasswordHasherInterface $userPasswordHash)
    {
        $this->userPasswordHash = $userPasswordHash;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        
        $object = (new User())
            ->setEmail('user@user.fr');
        $object->setPassword($this->userPasswordHash->hashPassword($object, 'test'));
        $manager->persist($object);

        for($i = 0; $i < 10; $i++) {
            $object = (new User())
                ->setEmail($faker->freeEmail());
            $object->setPassword($this->userPasswordHash->hashPassword($object, 'test'));
            $manager->persist($object);
        }

        $manager->flush();
    }
}