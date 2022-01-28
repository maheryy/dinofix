<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $customers = $manager->getRepository(Customer::class)->findAll();

        for ($i = 0; $i < 15; $i++) {
            $object = (new Review())
                ->setCustomer($faker->randomElement($customers))
                ->setMessage($faker->sentence(20))
                ->setRate($faker->numberBetween(0, 5))
                ->setStatus(1)
                ->setCreatedAt($faker->dateTime('now'))
                ->setUpdatedAt($faker->dateTime('now'));

            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CustomerFixtures::class
        ];
    }
}