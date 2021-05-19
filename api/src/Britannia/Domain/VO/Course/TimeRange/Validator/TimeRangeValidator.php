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

namespace Britannia\Domain\VO\Course\TimeRange\Validator;


use Britannia\Domain\VO\Course\TimeTable\TimeSheet;
use Britannia\Domain\VO\Course\TimeTable\TimeTable as VOTimeTable;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class TimeRangeValidator extends ConstraintValidator
{

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return TimeRange::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VOTimeTable) {
            return;
        }

        $this->validateField('start', $value['start'], [
            new NotBlank(),
            new Type([
                'type' => CarbonImmutable::class
            ])
        ]);

        $this->validateField('end', $value['end'], [
            new NotBlank(),
            new Type([
                'type' => CarbonImmutable::class
            ])
        ]);

//        $this->validateField('schedule', $value['schedule'], [
//            new NotBlank([
//                'message' => 'Se necesita al menos el horario de una clase'
//            ]),
//            new All([
//                new Type([
//                    'type' => TimeSheet::class
//                ])
//            ])
//        ]);

        $this->validateDates($value);
    }

    protected function validateDates($value)
    {
        $start = $value['start'];
        $end = $value['end'];

        if (!($start instanceof CarbonImmutable)) {
            return;
        }

        if (!($end instanceof CarbonImmutable)) {
            return;
        }

        if ($end->lessThanOrEqualTo($start)) {
            $this->addViolation('La fecha inicial no puede ser mayor que la final');
        }
    }

}
