<?php

namespace App\Handler;

use App\Dto\CalculatePriceRequest;
use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\Tax;
use App\Service\PaymentCalculation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CalculatePriceRequestHandler
{
    /**
     * @var PaymentCalculation
     */
    protected $calculation;

    public function __construct(PaymentCalculation $calculation)
    {
        $this->calculation = $calculation;
    }

    /**
     * Get price
     *
     * @param CalculatePriceRequest $calculatePriceRequest
     * @return float[]|int[]
     */
    public function __invoke(CalculatePriceRequest $calculatePriceRequest)
    {
        $product = $calculatePriceRequest->product;
        $taxNumber = $calculatePriceRequest->taxNumber;
        $couponCode = $calculatePriceRequest->couponCode;

        $price = $this->calculation->calculate($product, $taxNumber, $couponCode);

        return ['price' => $price];
    }
}