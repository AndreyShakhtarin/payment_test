<?php

namespace App\Validator;

use App\Entity\Tax;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TaxNumberValidator extends ConstraintValidator
{

    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {

        $taxRepository = $this->em->getRepository(Tax::class);
        $tax = $taxRepository->findOneByCode($value);

        $tax ?? $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->setCode(Response::HTTP_BAD_REQUEST)
            ->addViolation();
    }
}
