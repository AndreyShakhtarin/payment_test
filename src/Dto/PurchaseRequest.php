<?php

namespace App\Dto;

use App\Validator\CouponCode;
use App\Validator\Payment;
use App\Validator\Product;
use App\Validator\TaxNumber;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\Validator\Constraints as Assert;

class PurchaseRequest
{
    #[Assert\NotBlank]
    #[Product]
    public int $product;

    #[Assert\NotBlank]
    #[TaxNumber]
    public string $taxNumber;

    #[Assert\NotBlank]
    #[CouponCode]
    public string $couponCode;

    #[Assert\NotBlank]
    #[Assert\Choice(options: ['paypal', 'stripe'], message: 'The value you selected is not a valid choice. Select one choice: paypal or stripe')]
    #[Payment]
    public string $paymentProcessor;
}