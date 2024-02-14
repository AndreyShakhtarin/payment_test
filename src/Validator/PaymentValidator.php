<?php

namespace App\Validator;

use App\PaymentProcessor\PaypalPaymentProcessor;
use App\PaymentProcessor\StripePaymentProcessor;
use App\Service\PaymentCalculation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PaymentValidator extends ConstraintValidator
{
    protected $calculation;
    protected $paypalPaymentProcessor;
    protected $stripePaymentProcessor;

    protected $constraint;

    public function __construct(
        PaymentCalculation     $calculation,
        PaypalPaymentProcessor $paypalPaymentProcessor,
        StripePaymentProcessor $stripePaymentProcessor
    )
    {
        $this->calculation = $calculation;
        $this->paypalPaymentProcessor = $paypalPaymentProcessor;
        $this->stripePaymentProcessor = $stripePaymentProcessor;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var Payment $constraint */
        $this->constraint = $constraint;
        $dataContext = $this->context->getObject();

        $product = $dataContext->product;
        $taxNumber = $dataContext->taxNumber;
        $couponCode = $dataContext->couponCode;
        $paymentType = $dataContext->paymentProcessor;
        $price = $this->calculation->calculate($product, $taxNumber, $couponCode);

        switch ($paymentType) {
            case 'paypal':
                $this->paypalPayment($price);
                break;
            case 'stripe':
                $this->stripePayment($price);
        }
    }

    private function paypalPayment($price)
    {
        try {
            $this->paypalPaymentProcessor->pay($price);
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            $this->exceptionValidation($price, $message);
        }
    }

    private function stripePayment($price)
    {
        !$this->stripePaymentProcessor->processPayment($price) ? $this->exceptionValidation($price, $this->constraint->message) : false;
    }

    private function exceptionValidation($value, $message)
    {
        $this->context->buildViolation($message)
            ->setParameter('{{ value }}', $value)
            ->setCode(400)
            ->addViolation();
    }
}
