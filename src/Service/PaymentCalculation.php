<?php

namespace App\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\Tax;
use Doctrine\ORM\EntityManagerInterface;

class PaymentCalculation
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Get price of product
     *
     * @param $id
     * @return float|null
     */
    private function getProductPrice($id)
    {
        $productRepository = $this->em->getRepository(Product::class);
        $product = $productRepository->find($id);

        return $product?->getPrice() ?? 0;
    }

    /**
     * Get percent of tax
     *
     * @param $taxNumber
     * @return mixed
     */
    private function getTaxPercent($taxNumber)
    {
        $tax = $this->em->getRepository(Tax::class)->findOneByCode($taxNumber);

        return $tax?->getPercent() ?? 0;
    }

    /**
     * Get coupon entity
     *
     * @param $title
     * @return mixed
     */
    private function getCoupon($title)
    {
        $couponRepository = $this->em->getRepository(Coupon::class);

        return $couponRepository->findOneByTitle($title);
    }

    public function calculate($product, $taxNumber, $couponCode)
    {
        $productPrice = $this->getProductPrice($product);
        $taxPercent = $this->getTaxPercent($taxNumber);
        $coupon = $this->getCoupon($couponCode);

        $couponAmount = $coupon?->getAmount() ?? 0;
        $totalPriceWithTax = $productPrice + (($productPrice/100) * $taxPercent);

        return $coupon?->getType() ?? null == Coupon::PERCENT ? $totalPriceWithTax - ($totalPriceWithTax/100) * $couponAmount : $totalPriceWithTax - $couponAmount;
    }
}