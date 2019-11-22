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
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Infraestructure\Symfony\Importer\Resume;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\ConstraintViolationList;

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

        $this->courses = array_map(function ($course) {
            return $this->findOneOrNull(Course::class, [
                'oldId' => $course * 1
            ]);
        }, $courses);

        $this->courses = array_filter($this->courses);

        return $this;
    }


    public function build(): ?object
    {
        if (empty($this->courses)) {
            return $this->student;
        }

        $collection = new ArrayCollection();

        foreach ($this->courses as $course){
            $collection->add(StudentCourse::make($this->student, $course));
        }

        $this->student->setStudentHasCourses($collection);
        return $this->student;
    }
}
