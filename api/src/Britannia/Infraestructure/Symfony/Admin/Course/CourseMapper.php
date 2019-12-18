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

namespace Britannia\Infraestructure\Symfony\Admin\Course;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\CourseDto;
use Britannia\Domain\Service\Course\LessonGenerator;
use Britannia\Domain\Service\Course\UnitGenerator;
use PlanB\DDD\Domain\VO\Price;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

final class CourseMapper extends AdminMapper
{
    /**
     * @var LessonGenerator
     */
    private LessonGenerator $lessonCreator;

    public function __construct(LessonGenerator $lessonCreator, UnitGenerator $unitGenerator)
    {
        parent::__construct(Course::class);
        $this->lessonCreator = $lessonCreator;
        $this->unitGenerator = $unitGenerator;
    }

    public function mapDataToForms($data, $forms)
    {
        parent::mapDataToForms($data, $forms);
    }


    protected function create(array $values)
    {
        $timeTable = $values['timeTable'];
        unset($values['timeTable']);

        $dto = CourseDto::fromArray($values);
        return Course::make($dto)
            ->changeCalendar($timeTable, $this->lessonCreator);
    }

    /**
     * @param Course $course
     * @param array $values
     */
    protected function update($course, array $values)
    {
        if (!($course instanceof Course)) {
            throw new UnexpectedTypeException($course, Course::class);
        }



        $timeTable = $values['timeTable'];
        unset($values['timeTable']);

        $unitsDefinition = $values['unitsDefinition'];
        unset($values['unitsDefinition']);



        $dto = CourseDto::fromArray($values);

        $dto->enrollmentPayment = Price::make(50);
        $dto->monthlyPayment = Price::make(50);

        $course
            ->update($dto)
            ->changeCalendar($timeTable, $this->lessonCreator)
            ->changeUnits($unitsDefinition, $this->unitGenerator);
    }

}
