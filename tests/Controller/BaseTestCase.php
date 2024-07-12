<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Doctrine\ORM\Tools\SchemaTool;
use App\DataFixtures\ProductFixtures;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class BaseTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $client = static::createClient();
        $this->client = $client;
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);


        $this->resetDatabase();
        $this->loadFixtures();

        /** @var UserRepository $userRepository */
        $userRepository = $client->getContainer()->get(UserRepository::class);

        // Assuming you know the ID or another unique identifier

        /** @var UserInterface $user */
        $user = $userRepository->find(1);

        $client->loginUser($user);
        $this->client->setServerParameter('HTTP_ACCEPT', 'application/json');
    }

    private function resetDatabase(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();
        if (!empty($metadata)) {
            $schemaTool->createSchema($metadata);
        }
    }

    private function loadFixtures(): void
    {
        $container = self::getContainer();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get(EntityManagerInterface::class);

        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = $container->get(UserPasswordHasherInterface::class);


        $fixture = new UserFixtures($passwordHasher);
        $fixture->load($entityManager);

        $fixture = new ProductFixtures();
        $fixture->load($entityManager);
    }
}
