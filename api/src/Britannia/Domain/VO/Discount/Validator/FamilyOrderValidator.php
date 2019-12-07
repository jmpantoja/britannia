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

namespace Britannia\Domain\VO\Discount\Validator;


use Britannia\Domain\VO\Payment\PaymentMode;
use Britannia\Domain\VO\Payment\Validator\Payment;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraints\NotBlank;

class FamilyOrderValidator extends ConstraintValidator
{

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return FamilyOrder::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof \Britannia\Domain\VO\Discount\FamilyOrder) {
            return;
        }

        if (\Britannia\Domain\VO\Discount\FamilyOrder::hasName($value)) {
            return;
        }


        $this->addViolation($constraint->message);

    }
}
