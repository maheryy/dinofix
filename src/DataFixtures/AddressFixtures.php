<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $object = (new Address())
            ->setCountry($faker->country())
            ->setRegion($faker->country())
            ->setCity($faker->city())
            ->setPostcode($faker->postcode())
            ->setStreet($faker->streetName())
            ->setAdditional('Porte 4 étages 2')
            ->setLocation('10');
        $manager->persist($object);

        $manager->flush();
    }
}