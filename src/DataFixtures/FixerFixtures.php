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

        for ($i = 0; $i < 10; $i++) {
            $object = (new Fixer())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setAlias($faker->company())
                ->setEmail($faker->freeEmail())
                ->setPhone($faker->phoneNumber())
                ->setAddress($faker->randomElement($addresses))
                ->setSettings('no settings')
                ->setStatus(1)
                ->setCreatedAt($faker->dateTimeBetween('-1 month'))
                ->setUpdatedAt($faker->dateTimeBetween('-1 month'));

            $object->setPassword($this->userPasswordHash->hashPassword($object, 'test'));

            $rand_review = rand(1, 3);
            for ($j = 0; $j < $rand_review; $j++) {
                $review = (new Review())
                    ->setCustomer($faker->randomElement($customers))
                    ->setMessage($faker->sentence(20))
                    ->setRate($faker->numberBetween(0, 5))
                    ->setStatus(1)
                    ->setCreatedAt($faker->dateTime('now'))
                    ->setUpdatedAt($faker->dateTime('now'));

                $object->addReview($review);
                $manager->persist($review);
            }

            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            AddressFixtures::class,
            CustomerFixtures::class
        ];
    }
}