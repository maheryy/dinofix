<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Customer;
use App\Entity\Fixer;
use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FixerFixtures extends Fixture implements DependentFixtureInterface
{
    /**@var UserPasswordHasherInterface $passwordHash */
    private $userPasswordHash;

    public function __construct(UserPasswordHasherInterface $userPasswordHash)
    {
        $this->userPasswordHash = $userPasswordHash;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $addresses = $manager->getRepository(Address::class)->findAll();
        $customers = $manager->getRepository(Customer::class)->findAll();

        $object = (new Fixer())
            ->setFirstName($faker->firstName())
            ->setLastName($faker->lastName())
            ->setAlias($faker->company())
            ->setEmail('test@test.fr')
            ->setRoles(['ROLE_FIXER'])
            ->setPhone($faker->phoneNumber())
            ->setAddress($faker->randomElement($addresses))
            ->setSettings('no settings');

        $object->setPassword($this->userPasswordHash->hashPassword($object, 'test'));
        $manager->persist($object);

        $reviews = $this->getReviews();
        for ($i = 0; $i < 10; $i++) {
            $object = (new Fixer())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setAlias($faker->company())
                ->setEmail($faker->freeEmail())
                ->setRoles(['ROLE_FIXER'])
                ->setPhone($faker->phoneNumber())
                ->setAddress($faker->randomElement($addresses))
                ->setSettings('no settings');

            $object->setPassword($this->userPasswordHash->hashPassword($object, 'test'));

            $rand_review = rand(1, 3);
            $rate = 0;
            for ($j = 0; $j < $rand_review; $j++) {
                $random_rate = $faker->numberBetween(0, 5);
                $rate += $random_rate;

                $review = (new Review())
                    ->setCustomer($faker->randomElement($customers))
                    ->setMessage($faker->randomElement($reviews))
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

    public function getDependencies()
    {
        return [
            AddressFixtures::class,
            CustomerFixtures::class
        ];
    }
}