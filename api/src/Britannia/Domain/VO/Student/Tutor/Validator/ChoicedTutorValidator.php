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

namespace Britannia\Domain\VO\Student\Tutor\Validator;


use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;

class ChoicedTutorValidator extends ConstraintValidator
{
    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return ChoicedTutor::class;
    }

    public function handle($value, Constraint $constraint)
    {
        $tutor = $value['tutor'];
        $description = $value['description'];

        if (is_null($tutor)) {
            return;
        }

        if (empty($description)) {
            $this->addViolation('ssss');
            $this->addViolationToField('description', 'Este campo es requerido');
        }
    }

}
