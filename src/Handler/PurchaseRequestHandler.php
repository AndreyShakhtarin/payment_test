<?php

namespace App\Handler;

use App\Dto\PurchaseRequest;
use App\PaymentProcessor\PaypalPaymentProcessor;
use App\PaymentProcessor\StripePaymentProcessor;
use App\Service\PaymentCalculation;
use App\Service\PaymentProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class PurchaseRequestHandler
{
    protected $calculation;

    protected $em;

    protected $paymentProcessor;

    public function __construct(
        PaymentCalculation $calculation,
        EntityManagerInterface $em
    )
    {
        $this->calculation = $calculation;
        $this->em = $em;
    }

    public function __invoke(PurchaseRequest $purchaseRequest)
    {
        $product = $purchaseRequest->product;
        $taxNumber = $purchaseRequest->taxNumber;
        $couponCode = $purchaseRequest->couponCode;

        $price = $this->calculation->calculate($product, $taxNumber, $couponCode);

        return $price;
    }
}