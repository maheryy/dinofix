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
        
        $object = (new Category())
            ->setName('Aquatique')
            ->setDescription($faker->sentence(20))
            ->setPicture('no pic');
        $manager->persist($object);

        $object = (new Category())
            ->setName('Terrestre')
            ->setDescription($faker->sentence(20))
            ->setPicture('no pic');
        $manager->persist($object);

        $object = (new Category())
            ->setName('Volant')
            ->setDescription($faker->sentence(20))
            ->setPicture('no pic');
        $manager->persist($object);

        $manager->flush();
    }
}