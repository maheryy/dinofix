<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Customer;
use App\Entity\Fixer;
use App\Entity\Review;
use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ServiceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $categories = [
            'aquatique' => $manager->getRepository(Category::class)->findOneBy(["name" => "Aquatique"]),
            'terrestre' => $manager->getRepository(Category::class)->findOneBy(["name" => "Terrestre"]),
            'volant' => $manager->getRepository(Category::class)->findOneBy(["name" => "Volant"]),
            'omnivore' => $manager->getRepository(Category::class)->findOneBy(["name" => "Omnivore"]),
            'carnivore' => $manager->getRepository(Category::class)->findOneBy(["name" => "Carnivore"]),
            'herbivore' => $manager->getRepository(Category::class)->findOneBy(["name" => "Herbivore"]),
            'estropie' => $manager->getRepository(Category::class)->findOneBy(["name" => "Estropie"]),
            'alien' => $manager->getRepository(Category::class)->findOneBy(["name" => "Alien"])
        ];

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

        $fixers = $manager->getRepository(Fixer::class)->findAll();
        $customers = $manager->getRepository(Customer::class)->findAll();
        $rand_service = rand(100, 150);
        for ($i = 0; $i < $rand_service; $i++) {
            $service_desc = $faker->randomElement($this->getServiceDetails($categories));
            $object = (new Service())
                ->setName($service_desc['title'])
                ->setDescription($service_desc['description'])
                ->setCategory($service_desc['category'])
                ->setDino($faker->randomElement($service_desc['category']->getdinOS()->toArray()))
                ->setFixer($faker->randomElement($fixers))
                ->setPrice($faker->numberBetween(10, 10000));

            $rand_review = rand(1, 7);
            $rate = 0;
            for ($j = 0; $j < $rand_review; $j++) {
                $random_rate = $faker->numberBetween(0, 5);
                $rate += $random_rate;

                $review = (new Review())
                    ->setCustomer($faker->randomElement($customers))
                    ->setMessage($faker->randomElement($this->getReviews()))
                    ->setRate($random_rate);

                $object->addReview($review);
                $manager->persist($review);
            }

            $object->setRating(round($rate / $rand_review, 2));
            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getReviews()
    {
        return [
            'Très bon service, mon dino est comme neuf!',
            'Le service est impeccable, je recommande !',
            'Le fixer m\'a rendu le mauvais dino après service... Un enfant de 7 ans serais capable de faire la différence entre un T-rex et un ptérodactyle !',
            'Efficace et pas chère, je recommande !',
            'Pas incroyable, le service a durée 3 fois plus longtemps que prévu...',
            'Prestation horrible, fixer désagréable et incapable de répondre à mes questions...',
            'Parfait !',
        ];
    }

    public function getServiceDetails($categories)
    {
        return [
            [
                "title" => "Vidange de T-rex",
                "category" => $categories['carnivore'],
                "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
            ],
            [
                "title" => "Révision de T-rex",
                "category" => $categories['carnivore'],
                "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
            ],
            [
                "title" => "Contrôle technique de T-rex",
                "category" => $categories['carnivore'],
                "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
            ],
            [
                "title" => "Entretien de T-rex",
                "category" => $categories['carnivore'],
                "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
            ],
            [
                "title" => "Mise à jour de T-rex",
                "category" => $categories['carnivore'],
                "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
            ],
            [
                "title" => "Réparation de T-rex",
                "category" => $categories['carnivore'],
                "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
            ],
            [
                "title" => "Conversion de T-rex",
                "category" => $categories['carnivore'],
                "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
            ],
            [
                "title" => "Décentralisation de T-rex",
                "category" => $categories['carnivore'],
                "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
            ],
            [
                "title" => "Vidange de stégosaure",
                "category" => $categories['herbivore'],
                "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
            ],
            [
                "title" => "Révision de stégosaure",
                "category" => $categories['herbivore'],
                "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
            ],
            [
                "title" => "Contrôle technique de stégosaure",
                "category" => $categories['herbivore'],
                "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
            ],
            [
                "title" => "Entretien de stégosaure",
                "category" => $categories['herbivore'],
                "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
            ],
            [
                "title" => "Mise à jour de stégosaure",
                "category" => $categories['herbivore'],
                "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
            ],
            [
                "title" => "Réparation de stégosaure",
                "category" => $categories['herbivore'],
                "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
            ],
            [
                "title" => "Conversion de stégosaure",
                "category" => $categories['herbivore'],
                "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
            ],
            [
                "title" => "Décentralisation de stégosaure",
                "category" => $categories['herbivore'],
                "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
            ],
            [
                "title" => "Vidange d'ankylosaure",
                "category" => $categories['terrestre'],
                "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
            ],
            [
                "title" => "Révision d'ankylosaure",
                "category" => $categories['terrestre'],
                "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
            ],
            [
                "title" => "Contrôle technique d'ankylosaure",
                "category" => $categories['terrestre'],
                "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
            ],
            [
                "title" => "Entretien d'ankylosaure",
                "category" => $categories['terrestre'],
                "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
            ],
            [
                "title" => "Mise à jour d'ankylosaure",
                "category" => $categories['terrestre'],
                "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
            ],
            [
                "title" => "Réparation d'ankylosaure",
                "category" => $categories['terrestre'],
                "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
            ],
            [
                "title" => "Conversion d'ankylosaure",
                "category" => $categories['terrestre'],
                "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
            ],
            [
                "title" => "Décentralisation d'ankylosaure",
                "category" => $categories['terrestre'],
                "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
            ],
            [
                "title" => "Vidange de brachiosaure",
                "category" => $categories['herbivore'],
                "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
            ],
            [
                "title" => "Révision de brachiosaure",
                "category" => $categories['herbivore'],
                "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
            ],
            [
                "title" => "Contrôle technique de brachiosaure",
                "category" => $categories['herbivore'],
                "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
            ],
            [
                "title" => "Entretien de brachiosaure",
                "category" => $categories['herbivore'],
                "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
            ],
            [
                "title" => "Mise à jour de brachiosaure",
                "category" => $categories['herbivore'],
                "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
            ],
            [
                "title" => "Réparation de brachiosaure",
                "category" => $categories['herbivore'],
                "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
            ],
            [
                "title" => "Conversion de brachiosaure",
                "category" => $categories['herbivore'],
                "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
            ],
            [
                "title" => "Décentralisation de brachiosaure",
                "category" => $categories['herbivore'],
                "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
            ],
            [
                "title" => "Vidange d'archéopteryx",
                "category" => $categories['volant'],
                "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
            ],
            [
                "title" => "Révision d'archéopteryx",
                "category" => $categories['volant'],
                "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
            ],
            [
                "title" => "Contrôle technique d'archéopteryx",
                "category" => $categories['volant'],
                "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
            ],
            [
                "title" => "Entretien d'archéopteryx",
                "category" => $categories['volant'],
                "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
            ],
            [
                "title" => "Mise à jour d'archéopteryx",
                "category" => $categories['volant'],
                "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
            ],
            [
                "title" => "Réparation d'archéopteryx",
                "category" => $categories['volant'],
                "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
            ],
            [
                "title" => "Conversion d'archéopteryx",
                "category" => $categories['volant'],
                "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
            ],
            [
                "title" => "Décentralisation d'archéopteryx",
                "category" => $categories['volant'],
                "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
            ],
            [
                "title" => "Vidange de diplodocus",
                "category" => $categories['estropie'],
                "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
            ],
            [
                "title" => "Révision de diplodocus",
                "category" => $categories['estropie'],
                "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
            ],
            [
                "title" => "Contrôle technique de diplodocus",
                "category" => $categories['estropie'],
                "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
            ],
            [
                "title" => "Entretien de diplodocus",
                "category" => $categories['estropie'],
                "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
            ],
            [
                "title" => "Mise à jour de diplodocus",
                "category" => $categories['estropie'],
                "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
            ],
            [
                "title" => "Réparation de diplodocus",
                "category" => $categories['estropie'],
                "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
            ],
            [
                "title" => "Conversion de diplodocus",
                "category" => $categories['estropie'],
                "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
            ],
            [
                "title" => "Décentralisation de diplodocus",
                "category" => $categories['estropie'],
                "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
            ],
            [
                "title" => "Vidange de triceratops",
                "category" => $categories['omnivore'],
                "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
            ],
            [
                "title" => "Révision de triceratops",
                "category" => $categories['omnivore'],
                "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
            ],
            [
                "title" => "Contrôle technique de triceratops",
                "category" => $categories['omnivore'],
                "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
            ],
            [
                "title" => "Entretien de triceratops",
                "category" => $categories['omnivore'],
                "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
            ],
            [
                "title" => "Mise à jour de triceratops",
                "category" => $categories['omnivore'],
                "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
            ],
            [
                "title" => "Réparation de triceratops",
                "category" => $categories['omnivore'],
                "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
            ],
            [
                "title" => "Conversion de triceratops",
                "category" => $categories['omnivore'],
                "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
            ],
            [
                "title" => "Décentralisation de triceratops",
                "category" => $categories['omnivore'],
                "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
            ],
            [
                "title" => "Vidange de protoceratops",
                "category" => $categories['omnivore'],
                "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
            ],
            [
                "title" => "Révision de protoceratops",
                "category" => $categories['omnivore'],
                "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
            ],
            [
                "title" => "Contrôle technique de protoceratops",
                "category" => $categories['omnivore'],
                "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
            ],
            [
                "title" => "Entretien de protoceratops",
                "category" => $categories['omnivore'],
                "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
            ],
            [
                "title" => "Mise à jour de protoceratops",
                "category" => $categories['omnivore'],
                "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
            ],
            [
                "title" => "Réparation de protoceratops",
                "category" => $categories['omnivore'],
                "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
            ],
            [
                "title" => "Conversion de protoceratops",
                "category" => $categories['omnivore'],
                "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
            ],
            [
                "title" => "Décentralisation de protoceratops",
                "category" => $categories['omnivore'],
                "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
            ],
            [
                "title" => "Vidange de parasaurolophus",
                "category" => $categories['herbivore'],
                "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
            ],
            [
                "title" => "Révision de parasaurolophus",
                "category" => $categories['herbivore'],
                "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
            ],
            [
                "title" => "Contrôle technique de parasaurolophus",
                "category" => $categories['herbivore'],
                "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
            ],
            [
                "title" => "Entretien de parasaurolophus",
                "category" => $categories['herbivore'],
                "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
            ],
            [
                "title" => "Mise à jour de parasaurolophus",
                "category" => $categories['herbivore'],
                "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
            ],
            [
                "title" => "Réparation de parasaurolophus",
                "category" => $categories['herbivore'],
                "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
            ],
            [
                "title" => "Conversion de parasaurolophus",
                "category" => $categories['herbivore'],
                "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
            ],
            [
                "title" => "Décentralisation de parasaurolophus",
                "category" => $categories['herbivore'],
                "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
            ],
            [
                "title" => "Vidange de ptéranodon",
                "category" => $categories['volant'],
                "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
            ],
            [
                "title" => "Révision de ptéranodon",
                "category" => $categories['volant'],
                "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
            ],
            [
                "title" => "Contrôle technique de ptéranodon",
                "category" => $categories['volant'],
                "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
            ],
            [
                "title" => "Entretien de ptéranodon",
                "category" => $categories['volant'],
                "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
            ],
            [
                "title" => "Mise à jour de ptéranodon",
                "category" => $categories['volant'],
                "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
            ],
            [
                "title" => "Réparation de ptéranodon",
                "category" => $categories['volant'],
                "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
            ],
            [
                "title" => "Conversion de ptéranodon",
                "category" => $categories['volant'],
                "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
            ],
            [
                "title" => "Décentralisation de ptéranodon",
                "category" => $categories['volant'],
                "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
            ],
            [
                "title" => "Vidange de vélociraptor",
                "category" => $categories['carnivore'],
                "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
            ],
            [
                "title" => "Révision de vélociraptor",
                "category" => $categories['carnivore'],
                "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
            ],
            [
                "title" => "Contrôle technique de vélociraptor",
                "category" => $categories['carnivore'],
                "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
            ],
            [
                "title" => "Entretien de vélociraptor",
                "category" => $categories['carnivore'],
                "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
            ],
            [
                "title" => "Mise à jour de vélociraptor",
                "category" => $categories['carnivore'],
                "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
            ],
            [
                "title" => "Réparation de vélociraptor",
                "category" => $categories['carnivore'],
                "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
            ],
            [
                "title" => "Conversion de vélociraptor",
                "category" => $categories['carnivore'],
                "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
            ],
            [
                "title" => "Décentralisation de vélociraptor",
                "category" => $categories['carnivore'],
                "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
            ],
            [
                "title" => "Vidange de mammouth",
                "category" => $categories['herbivore'],
                "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
            ],
            [
                "title" => "Révision de mammouth",
                "category" => $categories['herbivore'],
                "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
            ],
            [
                "title" => "Contrôle technique de mammouth",
                "category" => $categories['herbivore'],
                "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
            ],
            [
                "title" => "Entretien de mammouth",
                "category" => $categories['herbivore'],
                "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
            ],
            [
                "title" => "Mise à jour de mammouth",
                "category" => $categories['herbivore'],
                "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
            ],
            [
                "title" => "Réparation de mammouth",
                "category" => $categories['herbivore'],
                "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
            ],
            [
                "title" => "Conversion de mammouth",
                "category" => $categories['herbivore'],
                "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
            ],
            [
                "title" => "Décentralisation de mammouth",
                "category" => $categories['herbivore'],
                "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
            ],
            [
                "title" => "Vidange de skrypzikus",
                "category" => $categories['alien'],
                "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
            ],
            [
                "title" => "Révision de skrypzikus",
                "category" => $categories['alien'],
                "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
            ],
            [
                "title" => "Contrôle technique de skrypzikus",
                "category" => $categories['alien'],
                "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
            ],
            [
                "title" => "Entretien de skrypzikus",
                "category" => $categories['alien'],
                "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
            ],
            [
                "title" => "Mise à jour de skrypzikus",
                "category" => $categories['alien'],
                "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
            ],
            [
                "title" => "Réparation de skrypzikus",
                "category" => $categories['alien'],
                "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
            ],
            [
                "title" => "Conversion de skrypzikus",
                "category" => $categories['alien'],
                "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
            ],
            [
                "title" => "Décentralisation de skrypzikus",
                "category" => $categories['alien'],
                "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
            ],
            [
                "title" => "Vidange d'elizabethosaure",
                "category" => $categories['aquatique'],
                "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
            ],
            [
                "title" => "Révision d'elizabethosaure",
                "category" => $categories['aquatique'],
                "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
            ],
            [
                "title" => "Contrôle technique d'elizabethosaure",
                "category" => $categories['aquatique'],
                "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
            ],
            [
                "title" => "Entretien d'elizabethosaure",
                "category" => $categories['aquatique'],
                "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
            ],
            [
                "title" => "Mise à jour d'elizabethosaure",
                "category" => $categories['aquatique'],
                "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
            ],
            [
                "title" => "Réparation d'elizabethosaure",
                "category" => $categories['aquatique'],
                "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
            ],
            [
                "title" => "Conversion d'elizabethosaure",
                "category" => $categories['aquatique'],
                "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
            ],
            [
                "title" => "Décentralisation d'elizabethosaure",
                "category" => $categories['aquatique'],
                "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
            ]];

    }

    private function generateDescription(Generator $faker): string
    {
        $html = [
            "<h3>Ce que vous trouverez</h3>",
            "<p>{$faker->realText(500)}</p>",
            "<h3>Inclus</h3>",
            "
            <ul>
                <li>{$faker->text(50)}</li>
                <li>{$faker->text(50)}</li>
                <li>{$faker->text(50)}</li>
                <li>{$faker->text(50)}</li>
                <li>{$faker->text(50)}</li>
                <li>{$faker->text(50)}</li>
            </ul>
            ",
            "<p><strong>Accompagné de nos meilleurs experts !</strong></p>",
            "<p><em>Offre valable uniquement sous réservation.</em></p>",
        ];

        return implode("&nbsp;", $html);
    }

    public function getDependencies()
    {
        return [
            CustomerFixtures::class,
            FixerFixtures::class,
            CategoryFixtures::class,
            DinoFixtures::class
        ];
    }
}