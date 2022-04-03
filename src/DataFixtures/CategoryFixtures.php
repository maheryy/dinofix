<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $categories = ['Aquatique', 'Terrestre', 'Volant', 'Omnivore', 'Carnivore', 'Herbivore', 'Estropié', 'Alien'];

        foreach ($categories as $category) {
            $object = (new Category())
                ->setName($category)
                ->setDescription($faker->sentence(20))
                ->setPicture('no pic');
            $manager->persist($object);
        }

        $manager->flush();
    }
}