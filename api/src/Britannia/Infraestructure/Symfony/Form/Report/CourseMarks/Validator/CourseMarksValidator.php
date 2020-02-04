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

namespace Britannia\Infraestructure\Symfony\Form\Report\CourseMarks\Validator;


use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;

class CourseMarksValidator extends ConstraintValidator
{

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return CourseMarks::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if (empty($value['selected'])) {
            return;
        }

        $start = CarbonImmutable::make($value['start']);
        $end = CarbonImmutable::make($value['end']);

        if (!($start instanceof CarbonImmutable)) {
            $this->addViolationToField('start', 'Se necesita el inicio del trimestre');
            return;
        }

        if (!($end instanceof CarbonImmutable)) {
            $this->addViolationToField('end', 'Se necesita el fin del trimestre');
            return;
        }

        if ($start->greaterThanOrEqualTo($end)) {
            $this->addViolationToField('end', 'Debe ser mayor que el inicio');
        }

    }
}
