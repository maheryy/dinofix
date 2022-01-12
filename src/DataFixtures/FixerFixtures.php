<?php

namespace App\DataFixtures;

use App\Entity\Fixer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FixerFixtures extends Fixture
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

        for($i = 0; $i < 10; $i++) {
            $object = (new Fixer())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEmail($faker->freeEmail())
                ->setPhone($faker->phoneNumber())
                ->setPicture('no pic')
                ->setSettings('no settings')
                ->setStatus(1)
                ->setCreatedAt($faker->dateTimeBetween('-1 month'))
                ->setUpdatedAt($faker->dateTimeBetween('-1 month'));


            $object->setPassword($this->userPasswordHash->hashPassword($object, 'test'));

            $manager->persist($object);
        }

        $manager->flush();
    }
}