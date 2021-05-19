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


class EnumValidator extends ConstraintValidator
{
    public function getConstraintType(): string
    {
        return Enum::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if (is_a($value, $constraint->enumClass)) {
            return;
        }


        if (forward_static_call([$constraint->enumClass, 'hasName'], $value)) {

            return;
        }

        $this->addViolation($constraint->message);
    }
}
