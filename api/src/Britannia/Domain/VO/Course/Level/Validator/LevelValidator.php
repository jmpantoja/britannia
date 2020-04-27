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

namespace Britannia\Domain\VO\Course\Level\Validator;


use Britannia\Domain\VO;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;

class LevelValidator extends ConstraintValidator
{

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return Level::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VO\Course\Level\Level) {
            return;
        }

        if (VO\Course\Level\Level::hasName($value)) {
            return;
        }

        $this->addViolation($constraint->message);
    }
}
