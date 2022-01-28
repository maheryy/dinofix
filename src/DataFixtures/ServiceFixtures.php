<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Dino;
use App\Entity\Fixer;
use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ServiceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $categoryAqua = $manager->getRepository(Category::class)->findOneBy(['name' => 'Aquatique']);
        $categoryTerre = $manager->getRepository(Category::class)->findOneBy(['name' => 'Terrestre']);
        $categoryVol = $manager->getRepository(Category::class)->findOneBy(['name' => 'Volant']);
        $dinos = $manager->getRepository(Dino::class)->findAll();
        $fixers = $manager->getRepository(Fixer::class)->findAll();

        for($i = 0; $i < 3; $i++) {
            $object = (new Service())
                ->setName('Service aqua '.$i)
                ->setDescription($faker->sentence(20))
                ->setStatus(1)
                ->setCreatedAt($faker->dateTime('now'))
                ->setUpdatedAt($faker->dateTime('now'))
                ->setCategory($categoryAqua)
                ->setDino($faker->randomElement($dinos))
                ->setFixer($faker->randomElement($fixers));

            $manager->persist($object);
        }

        for($j = 0; $j < 3; $j++) {
            $object = (new Service())
                ->setName('Service terre '.$j)
                ->setDescription($faker->sentence(20))
                ->setStatus(1)
                ->setCreatedAt($faker->dateTime('now'))
                ->setUpdatedAt($faker->dateTime('now'))
                ->setCategory($categoryTerre)
                ->setDino($faker->randomElement($dinos))
                ->setFixer($faker->randomElement($fixers));

            $manager->persist($object);
        }

        for($k = 0; $k < 3; $k++) {
            $object = (new Service())
                ->setName('Service vole '.$k)
                ->setDescription($faker->sentence(20))
                ->setStatus(1)
                ->setCreatedAt($faker->dateTime('now'))
                ->setUpdatedAt($faker->dateTime('now'))
                ->setCategory($categoryVol)
                ->setDino($faker->randomElement($dinos))
                ->setFixer($faker->randomElement($fixers));

            $manager->persist($object);
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