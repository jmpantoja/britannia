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

namespace Britannia\Domain\VO\SchoolCourse\Validator;


use Britannia\Domain\VO;
use Britannia\Domain\VO\Course\Locked\Validator\Locked;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;

class SchoolLevelValidator extends ConstraintValidator
{

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return SchoolLevel::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VO\SchoolCourse\SchoolLevel) {
            return;
        }

        if (VO\SchoolCourse\SchoolLevel::hasName($value)) {
            return;
        }

        $this->addViolation($constraint->message);
    }
}
