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

namespace Britannia\Domain\VO\Assessment\Validator;


use Britannia\Domain\VO;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;

class MarkValidator extends ConstraintValidator
{

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return Mark::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VO\Assessment\Mark) {
            return;
        }

        if (is_null($value)) {
            return;
        }

        if (!is_numeric($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
            return;
        }

        if ($value < 0 or $value > 10) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }


    }
}
