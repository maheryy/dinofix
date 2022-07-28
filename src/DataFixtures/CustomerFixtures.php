<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerFixtures extends Fixture
{
    /**@var UserPasswordHasherInterface $passwordHash */
    private $userPasswordHash;

    public function __construct(UserPasswordHasherInterface $userPasswordHash)
    {
        $this->userPasswordHash = $userPasswordHash;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $object = (new Customer())
            ->setFirstName($faker->firstName())
            ->setLastName($faker->lastName())
            ->setEmail('customer@dinofix.fr')
            ->setRoles(['ROLE_CUSTOMER'])
            ->setPhone($faker->phoneNumber())
            ->setSettings('no settings');

        $object->setPassword($this->userPasswordHash->hashPassword($object, 'joyfully-spied0'));
        $manager->persist($object);

        for($i = 0; $i < 10; $i++) {
            $object = (new Customer())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEmail($faker->freeEmail())
                ->setRoles(['ROLE_CUSTOMER'])
                ->setPhone($faker->phoneNumber())
                ->setSettings('no settings');

            $object->setPassword($this->userPasswordHash->hashPassword($object, 'Client@Dinofix2022'));

            $manager->persist($object);
        }

        $manager->flush();
    }
}