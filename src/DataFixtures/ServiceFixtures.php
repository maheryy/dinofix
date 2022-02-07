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
        $fixers = $manager->getRepository(Fixer::class)->findAll();
        $customers = $manager->getRepository(Customer::class)->findAll();

        foreach ($categories as $category) {
            $rand_service = rand(5, 15);
            for ($i = 0; $i < $rand_service; $i++) {

                $object = (new Service())
                    ->setName("{$faker->catchPhrase()} ({$category->getName()})")
                    ->setDescription($faker->realText(500) . "\n\n" . $faker->text(500))
                    ->setStatus(1)
                    ->setCategory($category)
                    ->setDino($faker->randomElement($category->getDinos()->toArray()))
                    ->setFixer($faker->randomElement($fixers));

                $rand_review = rand(1, 7);
                $rate = 0;
                for ($j = 0; $j < $rand_review; $j++) {
                    $random_rate = $faker->numberBetween(0, 5);
                    $rate += $random_rate;

                    $review = (new Review())
                        ->setCustomer($faker->randomElement($customers))
                        ->setMessage($faker->sentence(20))
                        ->setRate($random_rate)
                        ->setStatus(1);

                    $object->addReview($review);
                    $manager->persist($review);
                }

                $object->setRating(round($rate / $rand_review, 2));
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