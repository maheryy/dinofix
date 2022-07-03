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
        $steps = $this->getSteps();

        foreach ($steps as $step) {
            $object = (new ServiceStep())
                ->setStep($step['step'])
                ->setName($step['name'])
                ->setNotify($step['notify'])
                ->setDescription($step['description']);
            
            $manager->persist($object);
        }

        $manager->flush();
    }

    private function getSteps()
    {
        return [
            [
                'step' => 1,
                'name' => 'En attente',
                'description' => 'Demande en file d\'attente',
                'notify' => true,
            ],
            [
                'step' => 2,
                'name' => 'Diagnostique',
                'description' => 'Etude du problème',
                'notify' => true,
            ],
            [
                'step' => 3,
                'name' => 'Réparation',
                'description' => 'Réparation en cours',
                'notify' => false,
            ],
            [
                'step' => 4,
                'name' => 'Finalisation',
                'description' => 'Dernière retouches',
                'notify' => false,
            ],
            [
                'step' => 5,
                'name' => 'Prêt à récupérer',
                'description' => 'En attente de récupération',
                'notify' => true,
            ],
            [
                'step' => 6,
                'name' => 'Terminé',
                'description' => 'Problème résolu',
                'notify' => true,
            ],
        ];
    }
}