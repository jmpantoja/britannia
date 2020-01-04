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

namespace Britannia\Infraestructure\Symfony\Importer\Builder;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\CourseList;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Infraestructure\Symfony\Importer\Resume;

class StudentCoursesBuilder extends BuilderAbstract
{

    private const TYPE = 'Alumno / Curso';

    /**
     * @var Student
     */
    private $student;

    /**
     * @var Course[]
     */
    private $courses = [];


    public function initResume(array $input): Resume
    {
        $title = sprintf('%s %s', ...[
            $input['nombre'],
            $input['apellidos'],
        ]);

        return Resume::make((int)$input['id'], self::TYPE, $title);
    }

    public function withStudent(int $id): self
    {
        $this->student = $this->findOneOrNull(Student::class, [
            'oldId' => $id
        ]);

        return $this;
    }

    public function withCourses(string $courses): self
    {
        $courses = explode(',', $courses);
        $courses = array_filter($courses);
        $courses = array_unique($courses);

        $courses = array_map(function ($course) {
            return $this->findOneOrNull(Course::class, [
                'oldId' => $course * 1
            ]);
        }, $courses);

        $courses = array_filter($courses);
        $this->courses = CourseList::collect($courses);

        return $this;
    }


    public function build(): ?object
    {
        if (empty($this->courses) or empty($this->student)) {
            return $this->student;
        }

        $this->student->setCourses($this->courses);
        return $this->student;
    }
}
