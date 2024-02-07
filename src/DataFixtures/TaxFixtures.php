<?php

namespace App\DataFixtures;

use App\Entity\Tax;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaxFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $taxes = [
            ['country' => 'Germany', 'percent' => 19, 'code' => 'DEXXXXXXXXX'],
            ['country' => 'Italy',   'percent' => 22, 'code' => 'ITXXXXXXXXXXX'],
            ['country' => 'Greece',  'percent' => 24, 'code' => 'GRXXXXXXXXX'],
            ['country' => 'France',  'percent' => 20, 'code' => 'FRYYXXXXXXXXX'],
        ];

        foreach ($taxes as $data) {
            $tax = new Tax();
            $tax->setCountry($data['country']);
            $tax->setPercent($data['percent']);
            $tax->setCode($data['code']);
            $manager->persist($tax);
        }

        $manager->flush();
    }
}
