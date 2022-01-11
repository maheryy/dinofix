<?php

namespace App\DataFixtures;

use App\Entity\ServiceStep;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ServiceStepFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $object = (new ServiceStep())
            ->setStep('1')
            ->setName('Non attribué')
            ->setDescription('Service en attente de Dinofixer');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep('2')
        ->setName('Attribué')
        ->setDescription('Service en attente de validation par le client');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep('3')
        ->setName('Commencé')
        ->setDescription('Service en cours');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep('4')
        ->setName('Fini')
        ->setDescription('Service terminé');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep('5')
        ->setName('En pause')
        ->setDescription('Service en pause');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep('6')
        ->setName('Rejeté')
        ->setDescription('Service rejeté par le Dinofixer');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep('20')
        ->setName('Annulé')
        ->setDescription('Service annulé par le client');
        $manager->persist($object);

        $manager->flush();
    }
}