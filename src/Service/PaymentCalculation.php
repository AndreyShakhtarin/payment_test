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

        return $product->getPrice();
    }

    /**
     * Get percent of tax
     *
     * @param $taxNumber
     * @return mixed
     */
    private function getTaxPercent($taxNumber)
    {
        $taxRepository = $this->em->getRepository(Tax::class);
        $tax = $taxRepository->findOneByCode($taxNumber);

        return $tax->getPercent();
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

        $couponAmount = $coupon->getAmount();
        $totalPriceWithTax = $productPrice + (($productPrice/100) * $taxPercent);

        return $coupon->getType() == Coupon::PERCENT ? $totalPriceWithTax - ($totalPriceWithTax/100) * $couponAmount : $totalPriceWithTax - $couponAmount;
    }
}