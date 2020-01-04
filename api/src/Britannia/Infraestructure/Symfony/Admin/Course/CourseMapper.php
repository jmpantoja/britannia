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
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;

final class CourseMapper extends AdminMapper
{
    /**
     * @var LessonGenerator
     */
    private LessonGenerator $lessonCreator;
    /**
     * @var UnitGenerator
     */
    private UnitGenerator $unitGenerator;

    public function __construct(LessonGenerator $lessonCreator, UnitGenerator $unitGenerator)
    {
        parent::__construct();
        $this->lessonCreator = $lessonCreator;
        $this->unitGenerator = $unitGenerator;
    }

    public function className(): string
    {
        return Course::class;
    }

    protected function create(array $values): Course
    {
        $dto = $this->makeDto($values);
        return Course::make($dto);
    }

    /**
     * @param Course $course
     * @param array $values
     */
    protected function update($course, array $values)
    {
        $dto = $this->makeDto($values);
        $course
            ->update($dto);
    }

    /**
     * @param array $values
     * @return CourseDto
     */
    protected function makeDto(array $values): CourseDto
    {
        $values['lessonCreator'] = $this->lessonCreator;
        $values['unitGenerator'] = $this->unitGenerator;

        /**
         * Aqui el precio de la matricula por defecto
         */

        $dto = CourseDto::fromArray($values);
        return $dto;
    }

}
