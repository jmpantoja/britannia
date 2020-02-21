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

namespace Britannia\Domain\VO\Course\Intensive\Validator;


use Britannia\Domain\VO;
use Britannia\Domain\VO\Course\Pass\Validator\PassHours;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;

class IntensiveValidator extends ConstraintValidator
{

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return PassHours::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VO\Course\Intensive\Intensive) {
            return;
        }

        if (VO\Course\Intensive\Intensive::hasName($value)) {
            return;
        }

        $this->addViolation($constraint->message);
    }
}
