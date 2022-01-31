<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Customer;
use App\Entity\Dino;
use App\Entity\Fixer;
use App\Entity\Review;
use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ServiceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $categories = $manager->getRepository(Category::class)->findAll();
        $dinos = $manager->getRepository(Dino::class)->findAll();
        $fixers = $manager->getRepository(Fixer::class)->findAll();
        $customers = $manager->getRepository(Customer::class)->findAll();

        foreach ($categories as $category) {
            $rand_service = rand(3, 10);
            for ($i = 0; $i < $rand_service; $i++) {

                $object = (new Service())
                    ->setName("{$faker->catchPhrase()} ({$category->getName()})")
                    ->setDescription($faker->sentence(20))
                    ->setStatus(1)
                    ->setCreatedAt($faker->dateTime('now'))
                    ->setUpdatedAt($faker->dateTime('now'))
                    ->setCategory($category)
                    ->setDino($faker->randomElement($dinos))
                    ->setFixer($faker->randomElement($fixers));

                $rand_review = rand(1, 5);
                for ($j = 0; $j < $rand_review; $j++) {
                    $review = (new Review())
                        ->setCustomer($faker->randomElement($customers))
                        ->setMessage($faker->sentence(20))
                        ->setRate($faker->numberBetween(0, 5))
                        ->setStatus(1)
                        ->setCreatedAt($faker->dateTime('now'))
                        ->setUpdatedAt($faker->dateTime('now'));

                    $object->addReview($review);
                    $manager->persist($review);
                }

                $manager->persist($object);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CustomerFixtures::class,
            FixerFixtures::class,
            CategoryFixtures::class,
            DinoFixtures::class
        ];
    }
}