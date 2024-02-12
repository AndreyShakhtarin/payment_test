<?php

namespace App\Handler;

use App\Dto\CalculatePriceRequest;
use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\Tax;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CalculatePriceRequestHandler
{

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

    /**
     * Get price
     *
     * @param CalculatePriceRequest $calculatePriceRequest
     * @return float[]|int[]
     */
    public function __invoke(CalculatePriceRequest $calculatePriceRequest)
    {
        $productPrice = $this->getProductPrice($calculatePriceRequest->product);
        $taxPercent = $this->getTaxPercent($calculatePriceRequest->taxNumber);
        $coupon = $this->getCoupon($calculatePriceRequest->couponCode);

        $couponAmount = $coupon->getAmount();
        $totalPriceWithTax = $productPrice + (($productPrice/100) * $taxPercent);

        $price = $coupon->getType() == Coupon::PERCENT ? $totalPriceWithTax - ($totalPriceWithTax/100) * $couponAmount : $totalPriceWithTax - $couponAmount;

        return ['price' => $price];
    }
}