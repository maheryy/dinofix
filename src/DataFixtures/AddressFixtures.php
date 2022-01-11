<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $customer = $manager->getRepository(User::class)->find(1);

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

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}