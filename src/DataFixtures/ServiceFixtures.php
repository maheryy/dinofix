<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Customer;
use App\Entity\Fixer;
use App\Entity\Review;
use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

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
                    ->setName($faker->catchPhrase())
                    ->setDescription($this->generateDescription($faker))
                    ->setCategory($category)
                    ->setDino($faker->randomElement($category->getDinos()->toArray()))
                    ->setFixer($faker->randomElement($fixers))
                    ->setPrice($faker->numberBetween(10, 10000));

                $rand_review = rand(1, 7);
                $rate = 0;
                for ($j = 0; $j < $rand_review; $j++) {
                    $random_rate = $faker->numberBetween(0, 5);
                    $rate += $random_rate;

                    $review = (new Review())
                        ->setCustomer($faker->randomElement($customers))
                        ->setMessage($faker->sentence(20))
                        ->setRate($random_rate);

                    $object->addReview($review);
                    $manager->persist($review);
                }

                $object->setRating(round($rate / $rand_review, 2));
                $manager->persist($object);
            }
        }

        $manager->flush();
    }

    private function generateDescription(Generator $faker): string
    {
        $html = [
            "<h3>Ce que vous trouverez</h3>",
            "<p>{$faker->realText(500)}</p>",
            "<h3>Inclus</h3>",
            "
            <ul>
                <li>{$faker->text(50)}</li>
                <li>{$faker->text(50)}</li>
                <li>{$faker->text(50)}</li>
                <li>{$faker->text(50)}</li>
                <li>{$faker->text(50)}</li>
                <li>{$faker->text(50)}</li>
            </ul>
            ",
            "<p><strong>Accompagné de nos meilleurs experts !</strong></p>",
            "<p><em>Offre valable uniquement sous réservation.</em></p>",
        ];

        return implode("&nbsp;", $html);
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