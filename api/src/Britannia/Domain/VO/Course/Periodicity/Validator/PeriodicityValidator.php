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

namespace Britannia\Domain\VO\Course\Periodicity\Validator;


use Britannia\Domain\VO\Course\Periodicity\Validator\Periodicity;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;
use Britannia\Domain\VO;

class PeriodicityValidator extends ConstraintValidator
{

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return Periodicity::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VO\Course\Periodicity\Periodicity) {
            return;
        }

        if (VO\Course\Periodicity\Periodicity::hasName($value)) {
            return;
        }

        $this->addViolation($constraint->message);
    }
}
