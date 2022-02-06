<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Dino;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DinoFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $categories = $manager->getRepository(Category::class)->findAll();

        for($i = 0; $i < 25; $i++) {
            $object = (new Dino())
                ->setName($faker->word)
                ->setDescription($faker->sentence(20))
                ->setPicture('no pic')
                ->setCategory($faker->randomElement($categories));
            $manager->persist($object);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class
        ];
    }
}