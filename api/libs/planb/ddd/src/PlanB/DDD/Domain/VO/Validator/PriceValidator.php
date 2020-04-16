<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PlanB\DDD\Domain\VO\Validator;


use PlanB\DDD\Domain\VO;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;


class PriceValidator extends ConstraintValidator
{
    public function getConstraintType(): string
    {
        return Price::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VO\Price) {
            return;
        }

        if (!is_numeric($value) || !is_float($value * 1)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
            return;
        }

        if($constraint->refund === true){
            return;
        }

        if ($value < 0) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
