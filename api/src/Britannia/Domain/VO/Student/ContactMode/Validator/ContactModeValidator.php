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

namespace Britannia\Domain\VO\Student\ContactMode\Validator;


use Britannia\Domain\VO;
use Britannia\Domain\VO\Student\ContactMode\Validator\ContactMode;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;

class ContactModeValidator extends ConstraintValidator
{
    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return ContactMode::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VO\Student\ContactMode\ContactMode) {
            return;
        }

        if (VO\Student\ContactMode\ContactMode::hasName($value)) {
            return;
        }

        $this->addViolation($constraint->message);
    }

}
