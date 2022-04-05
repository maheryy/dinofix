<?php

namespace App\DataFixtures;

use App\Entity\ServiceStep;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ServiceStepFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        //$object = (new ServiceStep())
        //    ->setStep('1')
        //    ->setName('Non attribué')
        //    ->setDescription('Demande en attente de Dinofixer');
        //$manager->persist($object);

        $object = (new ServiceStep())
        ->setStep(1)
        ->setName('En attente')
        ->setDescription('Demande en file d\'attente');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep(2)
        ->setName('Diagnostique')
        ->setDescription('Etude du problème');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep(3)
        ->setName('Réparation')
        ->setDescription('Réparation en cours');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep(4)
        ->setName('Finalisation')
        ->setDescription('Dernière retouches');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep(5)
        ->setName('Prêt à récupérer')
        ->setDescription('En attente de récupération');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep(6)
        ->setName('Terminé')
        ->setDescription('Problème résolu');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep(-1)
        ->setName('En pause')
        ->setDescription('Demande en pause');
        $manager->persist($object);

        $object = (new ServiceStep())
        ->setStep(-2)
        ->setName('Annulé')
        ->setDescription('Demande annulé par le client');
        $manager->persist($object);

        $manager->flush();
    }
}