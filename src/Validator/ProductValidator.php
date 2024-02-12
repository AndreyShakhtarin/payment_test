<?php

namespace App\Validator;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProductValidator extends ConstraintValidator
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        $productRepository = $this->em->getRepository(Product::class);
        $product = $productRepository->find($value);

        $product ?? $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->setCode(Response::HTTP_BAD_REQUEST)
            ->addViolation();
    }
}
