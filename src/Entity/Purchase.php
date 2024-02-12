<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\RequestBody;
use App\Dto\CalculatePriceRequest;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            name: 'purchase',
            routeName: 'purchase',
            outputFormats: ['json' => 'application/json'],
            openapi: new Operation(
                summary: 'Purchase product',
                requestBody: new RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'product' => ['type' => 'integer'],
                                    'taxNumber' => ['type' => 'string'],
                                    'couponCode' => ['type' => 'string'],
                                    'paymentProcessor' => ['type' => 'string'],
                                ]
                            ],
                            'example' => [
                                'product' => 1,
                                'taxNumber' => 'IT12345678900',
                                'couponCode' => 'D15',
                                'paymentProcessor' => 'paypal',
                            ]
                        ]
                    ])
                )
            )
        ),
        new Post(
            name: 'calculate_price',
            status: 200,
            messenger: 'input',
            input: CalculatePriceRequest::class,
            uriTemplate: '/calculate-price',
            outputFormats: ['json' => 'application/json'],
        ),
    ]
)]
final class Purchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'purchases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'purchases')]
    private ?Tax $coupon = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getCoupon(): ?Tax
    {
        return $this->coupon;
    }

    public function setCoupon(?Tax $coupon): static
    {
        $this->coupon = $coupon;

        return $this;
    }
}
