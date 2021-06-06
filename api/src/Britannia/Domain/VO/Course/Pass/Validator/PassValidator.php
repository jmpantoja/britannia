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


use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Entity\Lesson\LessonList;
use Britannia\Domain\VO\Course\Pass\PassHours;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class PassValidator extends ConstraintValidator
{

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return Pass::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof \Britannia\Domain\Entity\Course\Pass\Pass) {
            return;
        }

        $this->validateField('hours', $value['hours'], [
            new NotBlank(),
            new Type([
                'type' => PassHours::class
            ])
        ]);

        $this->validateField('start', $value['start'], [
            new NotBlank(),
            new Type([
                'type' => CarbonImmutable::class
            ])
        ]);

        $this->validateLessons($value);

    }

    protected function validateLessons($value)
    {
        $start = $value['start'];
        $lessons = $value['lessons'];

        if (!($start instanceof CarbonImmutable)) {
            return;
        }
        if (!is_array($lessons)) {
            return;
        }

        if ($this->thereAreLessonsOutOfRange($lessons, $start)) {
            $this->addViolation('No puede haber lecciones fuera del mes que cubre el bono');
        }
    }

    private function thereAreLessonsOutOfRange(array $lessons, CarbonImmutable $start)
    {
        $end = $start->lastOfMonth();

        $outOfRange = LessonList::collect(array_filter($lessons))
            ->values()
            ->reduce(function (bool $carry, Lesson $lesson) use ($start, $end) {
                return $carry || !$lesson->day()->between($start, $end);
            }, false);

        return $outOfRange;

    }
}
