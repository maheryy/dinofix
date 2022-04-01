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
            ->setDescription('Demande en attente de Dinofixer');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep('2')
        ->setName('Attribué')
        ->setDescription('Demande en attente de validation par le client');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep('3')
        ->setName('Commencé')
        ->setDescription('Demande en cours');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep('4')
        ->setName('Fini')
        ->setDescription('Demande terminé');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep('-1')
        ->setName('En pause')
        ->setDescription('Demande en pause');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep('-2')
        ->setName('Annulé')
        ->setDescription('Demande annulé par le client');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep('-3')
        ->setName('Rejeté')
        ->setDescription('Demande rejeté par le Dinofixer');
        $manager->persist($object);

        $manager->flush();
    }
}