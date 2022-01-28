<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AddressFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i <= 20; $i++) {
            $object = (new Address())
                ->setCountry($faker->country())
                ->setRegion($faker->country())
                ->setCity($faker->city())
                ->setPostcode($faker->postcode())
                ->setStreet($faker->streetName())
                ->setLocation('1023094XYZ');

            $manager->persist($object);
        }

        $manager->flush();
    }
}