<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create example product 1
        $product1 = new Product();
        $product1->setName('Sample Product 1');
        $product1->setDescription('Description for Sample Product 1');
        $product1->setPrice("19.99");
        $product1->setCreatedAt(new \DateTimeImmutable("2024-07-12T13:47:02+00:00")); // Current time
        $manager->persist($product1);

        // Create example product 2
        $product2 = new Product();
        $product2->setName('Sample Product 2');
        $product2->setDescription('Description for Sample Product 2');
        $product2->setPrice("29.99");
        $product2->setCreatedAt(new \DateTimeImmutable("2024-07-12T13:47:02+00:00")); // Current time
        $manager->persist($product2);

        // Optionally add more products as needed...

        // Flush the changes to the database
        $manager->flush();
    }
}
