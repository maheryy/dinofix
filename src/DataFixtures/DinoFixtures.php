<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Dino;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class DinoFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $categories = $manager->getRepository(Category::class)->findAll();
        $dinos = [
            'T-Rex',
            'Stégosaure',
            'Ankylosaure',
            'Brachiosaure',
            'Archéopteryx',
            'Diplodocus',
            'Triceratops',
            'Protoceratops',
            'Parasaurolophus',
            'Ptéranodon',
            'Vélociraptor',
            'Mammouth',
            'Skrypzikus',
            'Elizabethosaure'
        ];
        $categories = [
            $manager->getRepository(Category::class)->findOneBy(["name" => "Carnivore"]),
            $manager->getRepository(Category::class)->findOneBy(["name" => "Herbivore"]),
            $manager->getRepository(Category::class)->findOneBy(["name" => "Terrestre"]),
            $manager->getRepository(Category::class)->findOneBy(["name" => "Herbivore"]),
            $manager->getRepository(Category::class)->findOneBy(["name" => "Volant"]),
            $manager->getRepository(Category::class)->findOneBy(["name" => "Estropie"]),
            $manager->getRepository(Category::class)->findOneBy(["name" => "Omnivore"]),
            $manager->getRepository(Category::class)->findOneBy(["name" => "Omnivore"]),
            $manager->getRepository(Category::class)->findOneBy(["name" => "Herbivore"]),
            $manager->getRepository(Category::class)->findOneBy(["name" => "Volant"]),
            $manager->getRepository(Category::class)->findOneBy(["name" => "Carnivore"]),
            $manager->getRepository(Category::class)->findOneBy(["name" => "Herbivore"]),
            $manager->getRepository(Category::class)->findOneBy(["name" => "Alien"]),
            $manager->getRepository(Category::class)->findOneBy(["name" => "Aquatique"]),
        ];
        for($i = 0; $i < count($dinos); $i++) {
            $object = (new Dino())
                ->setName($dinos[$i])
                ->setDescription($faker->sentence(20))
                ->setPicture('no pic')
                ->setCategory($categories[$i]);
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