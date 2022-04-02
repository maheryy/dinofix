<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Request;
use App\Entity\RequestActive;
use App\Entity\Service;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class RequestFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $services = $manager->getRepository(Service::class)->findAll();
        $customers = $manager->getRepository(Customer::class)->findAll();

        foreach($customers as $customer) {
            $object = (new Request())
                ->setService($service = $faker->randomElement($services))
                ->setCustomer($customer)
                ->setCategory($service->getCategory())
                ->setDino($service->getDino())
                ->setReference($faker->unique()->numerify('######'))
                ->setSubject($faker->realText(25))
                ->setDescription($faker->realText(100))
                ->setExpectedAt(new DateTime('now'));
            $manager->persist($object);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CustomerFixtures::class,
            ServiceFixtures::class,
        ];
    }
}