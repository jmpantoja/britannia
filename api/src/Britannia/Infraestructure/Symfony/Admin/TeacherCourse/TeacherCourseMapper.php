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

namespace Britannia\Infraestructure\Symfony\Admin\TeacherCourse;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Lesson\Lesson;
use DomainException;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;

final class TeacherCourseMapper extends AdminMapper
{
    private ?Lesson $lesson;

    public function className(): string
    {
        return Course::class;
    }

    public function setSubject(?Lesson $lesson): self
    {
        $this->lesson = $lesson;
        return $this;
    }

    protected function create(array $values): Course
    {
        throw new DomainException('Este formulario no debe usarse para crear Lecciones');
    }

    /**
     * @param Lesson $course
     * @param array $values
     * @return Course
     */
    protected function update($course, array $values): object
    {
        if ($this->lesson instanceof Lesson) {
            $this->lesson->updateAttendances($values['attendances']);
        }

        return $course;
    }
}
