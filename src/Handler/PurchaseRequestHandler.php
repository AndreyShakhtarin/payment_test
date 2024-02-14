<?php

namespace App\Handler;

use App\Dto\PurchaseRequest;
use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\Tax;
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
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(PurchaseRequest $purchaseRequest)
    {
        $purchase = $this->hydrate($purchaseRequest);
        $this->em->persist($purchase);
        $this->em->flush();

//        return $purchase;
    }

    private function hydrate(PurchaseRequest $purchaseRequest)
    {
        $product = $this->em->getRepository(Product::class)->find($purchaseRequest->product);
        $coupon = $this->em->getRepository(Coupon::class)->findOneByTitle($purchaseRequest->couponCode);
        $tax = $this->em->getRepository(Tax::class)->findOneByCode($purchaseRequest->taxNumber);

        $purchase = new Purchase();
        $purchase->setProduct($product);
        $purchase->setCoupon($coupon);
        $purchase->setTax($tax);

        return $purchase;
    }
}