<?php

// src/DataFixtures/UserFixtures.php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create a new user
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            'password'
        ));

        // Persist the user
        $manager->persist($user);

        // Flush the changes to the database
        $manager->flush();
    }
}
