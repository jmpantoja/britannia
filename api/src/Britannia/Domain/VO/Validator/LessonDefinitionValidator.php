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


use Britannia\Domain\Entity\ClassRoom\ClassRoom;
use Britannia\Domain\Entity\ClassRoom\ClassRoomId;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;
use Britannia\Domain\VO;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class LessonDefinitionValidator extends ConstraintValidator
{

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return LessonDefinition::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VO\LessonDefinition) {
            return;
        }


        $this->validateField('dayOfWeek', $value['dayOfWeek'], [
            new NotBlank(),
            new Type([
                'type' => VO\DayOfWeek::class
            ])
        ]);

        $this->validateField('startTime', $value['startTime'], [
            new NotBlank(),
            new Type([
                'type' => \DateTime::class
            ])
        ]);

        $this->validateField('length', $value['length'], [
            new NotBlank(),
            new Type([
                'type' => PositiveInteger::class
            ])
        ]);



        $this->validateField('classroomId', $value['classroomId'], [
            new NotBlank(),
            new Type([
                'type' => ClassRoomId::class
            ])
        ]);


    }

}
