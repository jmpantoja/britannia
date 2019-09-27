<?php

namespace PlanB\DDDBundle\Symfony\Validator;

use Britannia\Domain\VO\PhoneNumber as VOPhoneNumber;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PhoneNumberValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \Britannia\Infraestructure\Symfony\Validator\PhoneNumber */

        if (null === $value || '' === $value) {
            return;
        }

        if (VOPhoneNumber::isValid($value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }


}
