<?php

namespace App\Dto;

use App\Validator\CouponCode;
use App\Validator\Product;
use App\Validator\TaxNumber;
use Symfony\Component\Validator\Constraints as Assert;

final class CalculatePriceRequest
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
}