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

namespace Britannia\Domain\VO\Course\Pass\Validator;


use Britannia\Domain\Entity\ClassRoom\ClassRoomId;
use Britannia\Domain\Entity\Lesson\Lesson;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class PassLessonValidator extends ConstraintValidator
{

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return PassLesson::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof Lesson) {
            return;
        }

        $this->validateField('day', $value['day'], [
            new NotBlank(),
            new Type([
                'type' => CarbonImmutable::class
            ])
        ]);

        $this->validateField('startTime', $value['startTime'], [
            new NotBlank(),
            new Type([
                'type' => CarbonImmutable::class
            ])
        ]);

        $this->validateField('endTime', $value['endTime'], [
            new NotBlank(),
            new Type([
                'type' => CarbonImmutable::class
            ])
        ]);

        $this->validateField('classroomId', $value['classroomId'], [
            new NotBlank(),
            new Type([
                'type' => ClassRoomId::class
            ])
        ]);

        $this->validateTimes($value);

    }

    protected function validateTimes($value)
    {
        $start = $value['startTime'];
        $end = $value['endTime'];

        if (!($start instanceof CarbonImmutable)) {
            return;
        }

        if (!($end instanceof CarbonImmutable)) {
            return;
        }

        if ($end->lessThanOrEqualTo($start)) {
            $this->addViolation('La hora de fin debe ser mayor que la de inicio');
        }
    }
}
