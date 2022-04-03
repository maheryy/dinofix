<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixtures extends Fixture
{
    /**@var UserPasswordHasherInterface $passwordHash */
    private $userPasswordHash;

    public function __construct(UserPasswordHasherInterface $userPasswordHash)
    {
        $this->userPasswordHash = $userPasswordHash;
    }

    public function load(ObjectManager $manager): void
    {
        $object = (new Admin())
            ->setEmail('test@test.fr')
            ->setPicture('no pic')
            ->setSettings('no settings');

        $object->setPassword($this->userPasswordHash->hashPassword($object, 'test'));

        $manager->persist($object);

        $manager->flush();
    }
}