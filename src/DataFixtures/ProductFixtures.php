<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            ['title' => 'Iphone', 'price' => 100],
            ['title' => 'Headphone', 'price' => 20],
            ['title' => 'Case', 'price' => 10],
        ];

        foreach ($products as $data) {
            $product = new Product();
            $product->setTitle($data['title']);
            $product->setPrice($data['price']);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
