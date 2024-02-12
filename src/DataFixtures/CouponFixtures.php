<?php

namespace App\DataFixtures;

use App\Entity\Coupon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CouponFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $coupons = [
            ['title' => 'P10',  'type' => 'Percent', 'amount' => 10],
            ['title' => 'P100', 'type' => 'Percent', 'amount' => 100],
            ['title' => 'P6', 'type' => 'Percent', 'amount' => 6],
            ['title' => 'F2', 'type' => 'Fixed',   'amount' => 2]
        ];

        foreach ($coupons as $data) {
            $coupon = new Coupon();
            $coupon->setTitle($data['title']);
            $coupon->setType($data['type']);
            $coupon->setAmount($data['amount']);
            $manager->persist($coupon);
        }

        $manager->flush();
    }
}
