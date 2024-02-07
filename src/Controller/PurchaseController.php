<?php

namespace App\Controller;

use App\Entity\Purchase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{

    #[Route(
        name: 'purchase',
        path: '/purchase',
        methods: ['POST'],
        defaults: [
            '_api_resource_class' => Purchase::class,
            '_api_operation_name' => 'purchase',
        ]
    )]
    public function purchase(Request $request): Response
    {

        return $this->json([]);
    }

    #[Route(
        name: 'calculate_price',
        path: '/calculate-price',
        methods: ['POST'],
        defaults: [
            '_api_resource_class' => Purchase::class,
            '_api_operation_name' => 'calculate_price',
        ],
    )]
    public function calculatePrice(Request $request): Response
    {
        return $this->json([]);
    }
}
