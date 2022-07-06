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

        $categories = $manager->getRepository(Category::class)->findAll();
        $fixers = $manager->getRepository(Fixer::class)->findAll();
        $customers = $manager->getRepository(Customer::class)->findAll();

        foreach ($categories as $category) {
            $rand_service = rand(5, 15);
            for ($i = 0; $i < $rand_service; $i++) {
                $service_desc = $faker->randomElement($this->getServiceDetails());
                $object = (new Service())
                    ->setName($service_desc['title'])
                    ->setDescription($service_desc['description'])
                    ->setCategory($category)
                    ->setDino($faker->randomElement($category->getdinOS()->toArray()))
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
    
    public function getServiceDetails() {
        return [[
            "title" => "Vidange de T-rex",
            "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
        ],
        [
            "title" => "Révision de T-rex",
            "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
        ],
        [
            "title" => "Contrôle technique de T-rex",
            "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
        ],
        [
            "title" => "Entretien de T-rex",
            "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
        ],
        [
            "title" => "Mise à jour de T-rex",
            "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
        ],
        [
            "title" => "Réparation de T-rex",
            "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
        ],
        [
            "title" => "Conversion de T-rex",
            "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
        ],
        [
            "title" => "Décentralisation de T-rex",
            "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
        ],
        [
            "title" => "Vidange de stégosaure",
            "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
        ],
        [
            "title" => "Révision de stégosaure",
            "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
        ],
        [
            "title" => "Contrôle technique de stégosaure",
            "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
        ],
        [
            "title" => "Entretien de stégosaure",
            "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
        ],
        [
            "title" => "Mise à jour de stégosaure",
            "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
        ],
        [
            "title" => "Réparation de stégosaure",
            "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
        ],
        [
            "title" => "Conversion de stégosaure",
            "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
        ],
        [
            "title" => "Décentralisation de stégosaure",
            "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
        ],
        [
            "title" => "Vidange d'ankylosaure",
            "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
        ],
        [
            "title" => "Révision d'ankylosaure",
            "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
        ],
        [
            "title" => "Contrôle technique d'ankylosaure",
            "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
        ],
        [
            "title" => "Entretien d'ankylosaure",
            "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
        ],
        [
            "title" => "Mise à jour d'ankylosaure",
            "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
        ],
        [
            "title" => "Réparation d'ankylosaure",
            "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
        ],
        [
            "title" => "Conversion d'ankylosaure",
            "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
        ],
        [
            "title" => "Décentralisation d'ankylosaure",
            "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
        ],
        [
            "title" => "Vidange de brachiosaure",
            "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
        ],
        [
            "title" => "Révision de brachiosaure",
            "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
        ],
        [
            "title" => "Contrôle technique de brachiosaure",
            "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
        ],
        [
            "title" => "Entretien de brachiosaure",
            "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
        ],
        [
            "title" => "Mise à jour de brachiosaure",
            "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
        ],
        [
            "title" => "Réparation de brachiosaure",
            "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
        ],
        [
            "title" => "Conversion de brachiosaure",
            "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
        ],
        [
            "title" => "Décentralisation de brachiosaure",
            "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
        ],
        [
            "title" => "Vidange d'archéopteryx",
            "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
        ],
        [
            "title" => "Révision d'archéopteryx",
            "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
        ],
        [
            "title" => "Contrôle technique d'archéopteryx",
            "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
        ],
        [
            "title" => "Entretien d'archéopteryx",
            "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
        ],
        [
            "title" => "Mise à jour d'archéopteryx",
            "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
        ],
        [
            "title" => "Réparation d'archéopteryx",
            "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
        ],
        [
            "title" => "Conversion d'archéopteryx",
            "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
        ],
        [
            "title" => "Décentralisation d'archéopteryx",
            "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
        ],
        [
            "title" => "Vidange de diplodocus",
            "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
        ],
        [
            "title" => "Révision de diplodocus",
            "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
        ],
        [
            "title" => "Contrôle technique de diplodocus",
            "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
        ],
        [
            "title" => "Entretien de diplodocus",
            "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
        ],
        [
            "title" => "Mise à jour de diplodocus",
            "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
        ],
        [
            "title" => "Réparation de diplodocus",
            "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
        ],
        [
            "title" => "Conversion de diplodocus",
            "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
        ],
        [
            "title" => "Décentralisation de diplodocus",
            "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
        ],
        [
            "title" => "Vidange de triceratops",
            "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
        ],
        [
            "title" => "Révision de triceratops",
            "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
        ],
        [
            "title" => "Contrôle technique de triceratops",
            "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
        ],
        [
            "title" => "Entretien de triceratops",
            "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
        ],
        [
            "title" => "Mise à jour de triceratops",
            "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
        ],
        [
            "title" => "Réparation de triceratops",
            "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
        ],
        [
            "title" => "Conversion de triceratops",
            "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
        ],
        [
            "title" => "Décentralisation de triceratops",
            "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
        ],
        [
            "title" => "Vidange de protoceratops",
            "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
        ],
        [
            "title" => "Révision de protoceratops",
            "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
        ],
        [
            "title" => "Contrôle technique de protoceratops",
            "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
        ],
        [
            "title" => "Entretien de protoceratops",
            "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
        ],
        [
            "title" => "Mise à jour de protoceratops",
            "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
        ],
        [
            "title" => "Réparation de protoceratops",
            "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
        ],
        [
            "title" => "Conversion de protoceratops",
            "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
        ],
        [
            "title" => "Décentralisation de protoceratops",
            "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
        ],
        [
            "title" => "Vidange de parasaurolophus",
            "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
        ],
        [
            "title" => "Révision de parasaurolophus",
            "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
        ],
        [
            "title" => "Contrôle technique de parasaurolophus",
            "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
        ],
        [
            "title" => "Entretien de parasaurolophus",
            "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
        ],
        [
            "title" => "Mise à jour de parasaurolophus",
            "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
        ],
        [
            "title" => "Réparation de parasaurolophus",
            "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
        ],
        [
            "title" => "Conversion de parasaurolophus",
            "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
        ],
        [
            "title" => "Décentralisation de parasaurolophus",
            "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
        ],
        [
            "title" => "Vidange de ptéranodon",
            "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
        ],
        [
            "title" => "Révision de ptéranodon",
            "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
        ],
        [
            "title" => "Contrôle technique de ptéranodon",
            "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
        ],
        [
            "title" => "Entretien de ptéranodon",
            "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
        ],
        [
            "title" => "Mise à jour de ptéranodon",
            "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
        ],
        [
            "title" => "Réparation de ptéranodon",
            "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
        ],
        [
            "title" => "Conversion de ptéranodon",
            "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
        ],
        [
            "title" => "Décentralisation de ptéranodon",
            "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
        ],
        [
            "title" => "Vidange de vélociraptor",
            "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
        ],
        [
            "title" => "Révision de vélociraptor",
            "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
        ],
        [
            "title" => "Contrôle technique de vélociraptor",
            "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
        ],
        [
            "title" => "Entretien de vélociraptor",
            "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
        ],
        [
            "title" => "Mise à jour de vélociraptor",
            "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
        ],
        [
            "title" => "Réparation de vélociraptor",
            "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
        ],
        [
            "title" => "Conversion de vélociraptor",
            "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
        ],
        [
            "title" => "Décentralisation de vélociraptor",
            "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
        ],
        [
            "title" => "Vidange de mammouth",
            "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
        ],
        [
            "title" => "Révision de mammouth",
            "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
        ],
        [
            "title" => "Contrôle technique de mammouth",
            "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
        ],
        [
            "title" => "Entretien de mammouth",
            "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
        ],
        [
            "title" => "Mise à jour de mammouth",
            "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
        ],
        [
            "title" => "Réparation de mammouth",
            "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
        ],
        [
            "title" => "Conversion de mammouth",
            "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
        ],
        [
            "title" => "Décentralisation de mammouth",
            "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
        ],
        [
            "title" => "Vidange de skrypzikus",
            "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
        ],
        [
            "title" => "Révision de skrypzikus",
            "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
        ],
        [
            "title" => "Contrôle technique de skrypzikus",
            "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
        ],
        [
            "title" => "Entretien de skrypzikus",
            "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
        ],
        [
            "title" => "Mise à jour de skrypzikus",
            "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
        ],
        [
            "title" => "Réparation de skrypzikus",
            "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
        ],
        [
            "title" => "Conversion de skrypzikus",
            "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
        ],
        [
            "title" => "Décentralisation de skrypzikus",
            "description" => "Décentralisation de votre dino. Notre service inclue la décentralisation de votre dino afin de le mettre en place dans un endroit plus sûr : sur la Blockchain ! Vous pouvez ainsi revendre ou échanger votre dino avec un autre utilisateur sous forme de NFT. C'est comme les cartes Pokémon !",
        ],
        [
            "title" => "Vidange d'elizabethosaure",
            "description" => "Prise en charge de la vidange de votre dino. Notre service inclue le changement d'huile de coude ainsi qu'une purification de la vessie.",
        ],
        [
            "title" => "Révision d'elizabethosaure",
            "description" => "Révision de votre dino. Notre service inclue un test de vision ainsi qu'une vérification des griffes, ailes et pattes.",
        ],
        [
            "title" => "Contrôle technique d'elizabethosaure",
            "description" => "Contrôle technique de votre dino. Notre service vérifie la qualité de votre dino ainsi que la présence de tous ses os afin de conformer à la réglementation française des animaux préhistoriques.",
        ],
        [
            "title" => "Entretien d'elizabethosaure",
            "description" => "Entretien de votre dino. Notre service inclue le polissage des écailles, le nettoyage des pattes et des ailes, l'éguisage des griffes et des crocs.",
        ],
        [
            "title" => "Mise à jour d'elizabethosaure",
            "description" => "Mise à jour de votre dino. Notre service inclue la mise à jour de votre dino à la dernière version de DinOS afin de bénéficier des dernières fonctionalités.",
        ],
        [
            "title" => "Réparation d'elizabethosaure",
            "description" => "Réparation de votre dino. Notre service inclue la réparation de votre dino en cas de panne.",
        ],
        [
            "title" => "Conversion d'elizabethosaure",
            "description" => "Dino au bout de ses jours ? Nous avons la solution ! Avec notre service, votre dino sera reconverti en multiples denrées alimentaires. Quoi de mieux pour célébrer un vieux dino que de le manger au barbeuc ou en saucisse sèche ?",
        ],
        [
            "title" => "Décentralisation d'elizabethosaure",
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