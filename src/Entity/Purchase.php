<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Dto\CalculatePriceRequest;
use App\Dto\PurchaseRequest;
use App\Repository\PurchaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            name: 'purchase',
            status: 200,
            messenger: 'input',
            input: PurchaseRequest::class,
            uriTemplate: '/purchase',
            outputFormats: ['json' => 'application/json'],
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
    private ?Tax $tax = null;

    #[ORM\ManyToOne(inversedBy: 'purchases')]
    private ?Coupon $coupon = null;

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

    public function getTax(): ?Tax
    {
        return $this->tax;
    }

    public function setTax(?Tax $tax): static
    {
        $this->tax = $tax;

        return $this;
    }

    public function getCoupon(): ?Coupon
    {
        return $this->coupon;
    }

    public function setCoupon(?Coupon $coupon): static
    {
        $this->coupon = $coupon;

        return $this;
    }
}
