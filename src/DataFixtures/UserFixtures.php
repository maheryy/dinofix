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
            ->setEmail('user@user.fr')
            ->setFirstName($faker->firstName())
            ->setLastName($faker->lastName())
            ->setPhone($faker->phoneNumber())
            ->setPicture('no pic')
            ->setSettings('no settings')
            ->setStatus(1)
            ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 month')))
            ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 month')));
        $object->setPassword($this->userPasswordHash->hashPassword($object, 'test'));
        $manager->persist($object);

        for($i = 0; $i < 10; $i++) {
            $object = (new User())
                ->setEmail($faker->freeEmail())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setPhone($faker->phoneNumber())
                ->setPicture('no pic')
                ->setSettings('no settings')
                ->setStatus(1)
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 month')))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 month')));
            $object->setPassword($this->userPasswordHash->hashPassword($object, 'test'));
            $manager->persist($object);
        }

        $manager->flush();
    }
}