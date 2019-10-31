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

namespace Britannia\Domain\VO\Validator;


use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;
use Britannia\Domain\VO;

class ExaminerValidator extends ConstraintValidator
{

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return Examiner::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VO\Examiner) {
            return;
        }

        if (VO\Examiner::hasName($value)) {
            return;
        }

        $this->addViolation($constraint->message);
    }
}
