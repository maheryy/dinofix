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

        $categories = ['Aquatique', 'Terrestre', 'Volant', 'Omnivore', 'Carnivore', 'Herbivore', 'Estropie', 'Alien'];
        $i = 1;
        foreach ($categories as $category) {
            $object = (new Category())
                ->setName($category)
                ->setDescription($faker->sentence(20))
                ->setPicture($i.'-'.strtolower($category).'.png');
            $manager->persist($object);
            $i++;
        }

        $manager->flush();
    }
}