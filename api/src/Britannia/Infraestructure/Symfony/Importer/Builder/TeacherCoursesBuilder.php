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
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Infraestructure\Symfony\Importer\Resume;

class TeacherCoursesBuilder extends BuilderAbstract
{

    private const TYPE = 'Teacher / Curso';

    /**
     * @var StaffMember
     */
    private $teacher;

    /**
     * @var Course[]
     */
    private $courses = [];


    public function initResume(array $input): Resume
    {
        $title = sprintf('%s', $input['user']);

        return Resume::make((int)$input['id'], self::TYPE, $title);
    }

    public function withTeacher(int $id): self
    {
        $this->teacher = $this->findOneOrNull(StaffMember::class, [
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

        if (empty($this->courses) or empty($this->teacher)) {
            return $this->teacher;
        }

//        collect($this->courses)
//            ->each(function (Course $course){
//               dump($course->name()) ;
//            });

        $this->teacher->setCourses($this->courses);
        return $this->teacher;
    }
}
