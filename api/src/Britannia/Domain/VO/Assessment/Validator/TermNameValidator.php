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

class TermNameValidator extends ConstraintValidator
{

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return TermName::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VO\Assessment\TermName) {
            return;
        }

        if (VO\Assessment\TermName::hasName($value)) {
            return;
        }

        $this->addViolation($constraint->message);
    }
}
