<?php

namespace App\DataFixtures;

use App\Entity\Category;
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
        $reviews = $manager->getRepository(Review::class)->findAll();

        foreach ($categories as $category) {
            $rand_service = rand(3, 10);
            for ($i = 0; $i < $rand_service; $i++) {
                $fixer = $faker->randomElement($fixers);

                $object = (new Service())
                    ->setName("{$faker->realText(30)} ({$category->getName()})")
                    ->setDescription($faker->sentence(20))
                    ->setStatus(1)
                    ->setCreatedAt($faker->dateTime('now'))
                    ->setUpdatedAt($faker->dateTime('now'))
                    ->setCategory($category)
                    ->setDino($faker->randomElement($dinos))
                    ->setFixer($fixer);

                $rand_review = rand(0, 5);
                for ($j = 0; $j < $rand_review; $j++) {
                    $object->addReview($faker->randomElement($reviews));
                }

                $manager->persist($object);
                $manager->persist($fixer);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            FixerFixtures::class,
            CategoryFixtures::class,
            DinoFixtures::class
        ];
    }
}